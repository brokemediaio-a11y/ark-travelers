/**
 * Airplane Scroll - Canvas-based scroll-driven animation
 * Wheel events drive frames; scroll bar doesn't move until all frames are covered
 * Based on React implementation
 */

(function() {
    'use strict';

    const FRAME_EXT = '.jpg';
    const MOBILE_FRAME_EXT = '.png'; // Portrait frames are PNG
    const MAX_FRAMES = 93;           // Desktop landscape frame count
    const MOBILE_BREAKPOINT = 1024;  // px – portrait frames on mobile + tablet
    const WHEEL_SENSITIVITY = 0.0022;
    const SCROLL_TOP_THRESHOLD = 24; // px from top - only then allow reverse
    const END_UNLOCK_PROGRESS = 0.985; // start passing native scroll before exact final frame
    const MIN_FRAMES_TO_START = 10;  // Desktop minimum frames before revealing
    const MAX_LOAD_TIME_MS = 1500;   // Desktop maximum wait before reveal
    const PARALLEL_LOADS = 10;       // Load 10 frames in parallel for faster loading

    // Get frame source URL
    function getFrameSrc(index, framesFolder, ext) {
        const num = String(index + 1).padStart(3, '0');
        return framesFolder + 'ezgif-frame-' + num + (ext || FRAME_EXT);
    }

    function clamp(x, a, b) {
        return Math.max(a, Math.min(b, x));
    }

    // Smooth interpolation helper
    function lerp(start, end, factor) {
        return start + (end - start) * factor;
    }

    class AirplaneScroll {
        constructor(config) {
            this.config = {
                frameCount: config.frameCount || MAX_FRAMES,
                framesFolder: config.framesFolder || '',
                ...config
            };

            this.container = document.getElementById('airplane-scroll');
            this.canvas = document.getElementById('airplane-scroll-canvas');
            this.loadingEl = document.getElementById('airplane-scroll-loading');
            this.errorEl = document.getElementById('airplane-scroll-error');
            this.stickyEl = document.getElementById('airplane-scroll-sticky');
            this.progressFill = document.getElementById('airplane-scroll-progress-fill');
            this.loadingPct = document.getElementById('airplane-scroll-loading-pct');

            if (!this.container || !this.canvas) return;

            this.frames = [];
            this.loadProgress = 0;
            this.loadError = false;
            this.progress = 0;
            this.targetProgress = 0;
            this.lastFrameIndex = -1;
            this.rafId = null;
            this.lastOverlayUpdate = 0;
            this.lastDispatchedProgress = 0;
            this.lastFrameDrawAt = 0;
            this._dispatchScheduled = false;
            this.contentShown = false;
            this.loadStartTime = performance.now();
            this.loadingPromises = [];
            this.framesLoaded = false;
            this.isMobile = window.matchMedia('(max-width: ' + MOBILE_BREAKPOINT + 'px)').matches;

            // On mobile/tablet: switch to portrait frames and scroll-driven animation
            if (this.isMobile && config.mobileFramesFolder) {
                this.config.framesFolder = config.mobileFramesFolder;
                this.config.frameCount   = config.mobileFrameCount || config.frameCount;
                this.config.frameExt     = MOBILE_FRAME_EXT;
            } else {
                this.config.frameExt = FRAME_EXT;
            }
            this.minFramesToStart = this.isMobile ? 20 : MIN_FRAMES_TO_START;
            this.maxLoadTimeMs = this.isMobile ? 2200 : MAX_LOAD_TIME_MS;

            // Overlay elements
            this.copy0 = document.getElementById('airplane-copy-0');
            this.copy30 = document.getElementById('airplane-copy-30');
            this.copy60 = document.getElementById('airplane-copy-60');
            this.copy90 = document.getElementById('airplane-copy-90');
            this.heroCta = document.getElementById('airplane-hero-cta');
            this.darkOverlay = document.getElementById('airplane-dark-overlay');
            this.formOverlay = document.getElementById('airplane-form-overlay');

            this.init();
        }

        init() {
            this.setupCanvas();
            this.preloadFrames();
            this.setupEventListeners();
            this.animate();
        }

        setupCanvas() {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            const rect = this.container.getBoundingClientRect();
            if (rect.width < 2 || rect.height < 2) {
                // Avoid collapsing canvas to 0x0 during transient viewport/layout changes.
                return;
            }
            const w = Math.floor(rect.width * dpr);
            const h = Math.floor(rect.height * dpr);
            
            // Only resize if dimensions changed (prevents unnecessary context resets)
            if (this.canvas.width !== w || this.canvas.height !== h) {
                this.canvas.width = w;
                this.canvas.height = h;
                this.canvas.style.width = rect.width + 'px';
                this.canvas.style.height = rect.height + 'px';
            }
        }

        preloadFrames() {
            const total = this.config.frameCount;
            
            // Validate config before proceeding
            if (!this.config.framesFolder || total <= 0) {
                console.error('AirplaneScroll: Invalid configuration', this.config);
                this.loadError = true;
                this.showError();
                return;
            }
            
            const images = new Array(total).fill(null);
            let loaded = 0;
            let failed = 0;
            let contentShown = false;
            const startTime = performance.now();
            const maxRetries = 2; // Retry failed frames up to 2 times
            const retryDelays = [500, 1000]; // Delay before retry (ms)

            const showContent = () => {
                if (contentShown) return;
                
                // Get all loaded frames so far
                const loadedFrames = images.filter(img => img !== null && img.complete);
                
                // Show content if we have at least one frame OR timeout reached
                if (loadedFrames.length > 0) {
                    contentShown = true;
                    this.contentShown = true;
                    this.frames = images; // Use sparse array, will fill as more load
                    this.hideLoading();
                    this.showSticky();
                    // Draw first available frame immediately
                    if (this.canvas) {
                        this.setupCanvas();
                        // Find first loaded frame
                        const firstLoadedIndex = images.findIndex(img => img !== null && img.complete);
                        if (firstLoadedIndex >= 0) {
                            this.drawFrame(firstLoadedIndex);
                        }
                    }
                } else {
                    // No frames loaded - check if we should retry or show error
                    const elapsed = performance.now() - startTime;
                    if (elapsed < MAX_LOAD_TIME_MS * 2) {
                        // Still within retry window - don't show error yet
                        console.warn('AirplaneScroll: No frames loaded yet, retrying...');
                        return;
                    }
                    // All retries exhausted - show error
                    console.error('AirplaneScroll: Failed to load any frames after retries');
                    this.loadError = true;
                    this.showError();
                }
            };

            // Load single image helper with retry logic
            const loadImage = (index, retryCount = 0) => {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    
                    img.onload = () => {
                        images[index] = img;
                        loaded++;
                        this.loadProgress = loaded / total;
                        this.updateLoadingProgress();
                        
                        // Show content after minimum frames loaded OR timeout
                        // Also show immediately if first frame loads (for instant display)
                        const elapsed = performance.now() - startTime;
                        if (!contentShown && (
                            (index === 0 && loaded > 0) || // First frame loaded - show immediately
                            (loaded >= this.minFramesToStart) || 
                            (elapsed >= this.maxLoadTimeMs)
                        )) {
                            showContent();
                        }
                        
                        // If content already shown, update frame and redraw if needed
                        if (contentShown && this.frames) {
                            this.frames[index] = img;
                            if (this.lastFrameIndex === index) {
                                this.drawFrame(index);
                            }
                        }
                        
                        resolve(index);
                    };
                    
                    img.onerror = () => {
                        failed++;
                        loaded++; // Count failed attempts as "processed"
                        this.loadProgress = loaded / total;
                        this.updateLoadingProgress();
                        
                        // Retry logic for failed frames
                        if (retryCount < maxRetries) {
                            const delay = retryDelays[retryCount] || 500;
                            setTimeout(() => {
                                console.log(`AirplaneScroll: Retrying frame ${index + 1} (attempt ${retryCount + 2})`);
                                loadImage(index, retryCount + 1).then(resolve);
                            }, delay);
                            return;
                        }
                        
                        // Max retries reached - log warning but continue
                        console.warn(`AirplaneScroll: Failed to load frame ${index + 1} after ${maxRetries + 1} attempts`);
                        
                        // Show content if timeout reached (even with some failed frames)
                        const elapsed = performance.now() - startTime;
                        if (!contentShown && elapsed >= this.maxLoadTimeMs) {
                            showContent();
                        }
                        
                        resolve(index);
                    };
                    
                    // Normalize folder path (handle URL encoding)
                    let folderPath = this.config.framesFolder;
                    if (!folderPath.endsWith('/')) {
                        folderPath += '/';
                    }
                    // Decode URL-encoded spaces if present
                    folderPath = folderPath.replace(/%20/g, ' ');
                    
                    img.src = getFrameSrc(index, folderPath, this.config.frameExt);
                });
            };

            // Load frames in parallel batches
            const loadBatch = async (startIndex) => {
                const endIndex = Math.min(startIndex + PARALLEL_LOADS, total);
                const batchPromises = [];
                
                for (let i = startIndex; i < endIndex; i++) {
                    batchPromises.push(loadImage(i));
                }
                
                await Promise.all(batchPromises);
                
                // Load next batch
                if (endIndex < total) {
                    loadBatch(endIndex);
                } else {
                    // All frames processed - ensure content is shown
                    if (!contentShown) {
                        const loadedFrames = images.filter(img => img !== null && img.complete);
                        if (loadedFrames.length > 0) {
                            showContent();
                        } else {
                            this.loadError = true;
                            this.showError();
                        }
                    }
                    // Final update
                    this.frames = images;
                }
            };

            // Load first frame immediately (highest priority)
            loadImage(0).then(() => {
                // Once first frame loads, start batch loading
                loadBatch(1);
            });
            
            // Timeout fallback - reveal content after device-specific wait regardless
            setTimeout(() => {
                if (!contentShown) {
                    showContent();
                }
            }, this.maxLoadTimeMs);
        }

        updateLoadingProgress() {
            if (this.progressFill) {
                this.progressFill.style.width = (this.loadProgress * 100) + '%';
            }
            if (this.loadingPct) {
                this.loadingPct.textContent = Math.round(this.loadProgress * 100) + '%';
            }
        }

        showError() {
            if (this.loadingEl) this.loadingEl.style.display = 'none';
            if (this.errorEl) {
                this.errorEl.style.display = 'flex';
                // Setup retry button
                const retryBtn = document.getElementById('airplane-scroll-retry-btn');
                if (retryBtn && !retryBtn.dataset.listenerAdded) {
                    retryBtn.dataset.listenerAdded = 'true';
                    retryBtn.addEventListener('click', () => {
                        this.retry();
                    });
                }
            }
        }
        
        retry() {
            // Reset state
            this.loadError = false;
            this.contentShown = false;
            this.frames = [];
            this.loadProgress = 0;
            this.progress = 0;
            this.targetProgress = 0;
            this.lastFrameIndex = -1;
            
            // Hide error, show loading
            if (this.errorEl) this.errorEl.style.display = 'none';
            if (this.loadingEl) this.loadingEl.style.display = 'flex';
            if (this.stickyEl) this.stickyEl.style.display = 'none';
            
            // Restart frame loading
            this.preloadFrames();
        }

        hideLoading() {
            if (this.loadingEl) this.loadingEl.style.display = 'none';
        }

        showSticky() {
            if (this.stickyEl) this.stickyEl.style.display = 'flex';
        }

        setupEventListeners() {
            // Keep the same frame-scroll interaction model as desktop on all devices.
            this.setupWheelAndTouch();

            // Resize handler – keep canvas and scroll progress in sync
            window.addEventListener('resize', () => {
                this.setupCanvas();
                if (this.frames.length > 0) {
                    this.drawFrame(this.lastFrameIndex >= 0 ? this.lastFrameIndex : 0);
                }
            });

            // Self-healing repaint for long sessions / tab switches / GPU canvas drops.
            window.addEventListener('scroll', () => this.ensureHeroVisible(), { passive: true });
            window.addEventListener('focus', () => this.ensureHeroVisible(), { passive: true });
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) this.ensureHeroVisible();
            });

            // Initial progress event so header state is correct on load
            this.dispatchProgress();
        }

        isInView() {
            const rect = this.container.getBoundingClientRect();
            const winH = window.innerHeight;
            return rect.top <= 120 && rect.bottom >= winH - 80;
        }

        /** Desktop: wheel + touch drive animation (can preventDefault) */
        setupWheelAndTouch() {
            const onWheel = (e) => {
                const cur = this.targetProgress;
                const scrollY = window.scrollY;
                if (e.deltaY > 0 && cur >= END_UNLOCK_PROGRESS) {
                    // Near the end: don't trap scroll; complete animation and let page continue.
                    this.targetProgress = 1;
                    return;
                }
                if (cur >= 1) {
                    if (scrollY > SCROLL_TOP_THRESHOLD) return;
                    if (window.scrollY <= SCROLL_TOP_THRESHOLD && e.deltaY < 0) {
                        e.preventDefault();
                        this.targetProgress = clamp(cur + e.deltaY * WHEEL_SENSITIVITY, 0, 1);
                    }
                    return;
                }
                const next = clamp(cur + e.deltaY * WHEEL_SENSITIVITY, 0, 1);
                if (next >= 1 && e.deltaY > 0) {
                    this.targetProgress = 1;
                    this.progress = 1;
                    return;
                }
                if (next <= 0 && e.deltaY < 0) return;
                if (cur <= 0 && !this.isInView()) return;
                e.preventDefault();
                this.targetProgress = next;
            };

            let touchY = 0;
            const onTouchStart = (e) => { touchY = e.touches[0].clientY; };
            const onTouchMove = (e) => {
                const y = e.touches[0].clientY;
                const delta = touchY - y;
                touchY = y;
                const cur = this.targetProgress;
                const scrollY = window.scrollY;
                if (delta > 0 && cur >= END_UNLOCK_PROGRESS) {
                    // Near the end: don't trap touch scroll; allow immediate pass-through.
                    this.targetProgress = 1;
                    return;
                }
                if (cur >= 1) {
                    if (scrollY > SCROLL_TOP_THRESHOLD) return;
                    if (window.scrollY <= SCROLL_TOP_THRESHOLD && delta < 0) {
                        e.preventDefault();
                        this.targetProgress = clamp(cur + delta * WHEEL_SENSITIVITY, 0, 1);
                    }
                    return;
                }
                const next = clamp(cur + delta * WHEEL_SENSITIVITY, 0, 1);
                if (next >= 1 && delta > 0) {
                    this.targetProgress = 1;
                    this.progress = 1;
                    return;
                }
                if (next <= 0 && delta < 0) return;
                if (cur <= 0 && !this.isInView()) return;
                e.preventDefault();
                this.targetProgress = next;
            };

            window.addEventListener('wheel', onWheel, { passive: false });
            this.container.addEventListener('touchstart', onTouchStart, { passive: true });
            this.container.addEventListener('touchmove', onTouchMove, { passive: false });
        }

        dispatchProgress() {
            // Use requestAnimationFrame to batch event dispatch
            if (!this._dispatchScheduled) {
                this._dispatchScheduled = true;
                requestAnimationFrame(() => {
                    window.dispatchEvent(new CustomEvent('airplane-scroll-progress', { 
                        detail: { progress: this.progress } 
                    }));
                    this._dispatchScheduled = false;
                });
            }
        }

        drawFrame(index) {
            if (!this.canvas || !this.frames || this.frames.length === 0) return;
            
            const i = Math.min(Math.floor(index), this.frames.length - 1);
            if (i < 0) return;
            
            const img = this.frames[i];
            
            // If frame not loaded yet, use nearest available frame
            if (!img || !img.complete || !img.naturalWidth) {
                // Find nearest loaded frame
                let nearestIndex = -1;
                // Check frames before
                for (let j = i; j >= 0; j--) {
                    if (this.frames[j] && this.frames[j].complete && this.frames[j].naturalWidth) {
                        nearestIndex = j;
                        break;
                    }
                }
                // If not found, check frames after
                if (nearestIndex === -1) {
                    for (let j = i + 1; j < this.frames.length; j++) {
                        if (this.frames[j] && this.frames[j].complete && this.frames[j].naturalWidth) {
                            nearestIndex = j;
                            break;
                        }
                    }
                }
                if (nearestIndex >= 0 && this.frames[nearestIndex]) {
                    return this.drawFrame(nearestIndex);
                }
                return; // No frame available yet
            }
            
            // If image not ready, try to use nearest available frame
            if (!img.complete || !img.naturalWidth) {
                // Find nearest loaded frame
                for (let offset = 1; offset < 10; offset++) {
                    const lower = i - offset;
                    const higher = i + offset;
                    if (lower >= 0 && this.frames[lower] && this.frames[lower].complete && this.frames[lower].naturalWidth) {
                        this.drawFrame(lower);
                        return;
                    }
                    if (higher < this.frames.length && this.frames[higher] && this.frames[higher].complete && this.frames[higher].naturalWidth) {
                        this.drawFrame(higher);
                        return;
                    }
                }
                return; // No nearby frames available yet
            }
            
            const ctx = this.canvas.getContext('2d');
            if (!ctx) return;
            
            const w = this.canvas.width;
            const h = this.canvas.height;
            
            // Use clearRect instead of fillRect for better performance
            ctx.clearRect(0, 0, w, h);
            ctx.fillStyle = '#14171A';
            ctx.fillRect(0, 0, w, h);
            
            // Crop left black border on desktop source frames only
            const isLastFrame = i >= this.frames.length - 1;
            const blackBorderPixels = (this.isMobile || isLastFrame) ? 0 : Math.floor(img.naturalWidth * 0.025);
            const sourceX = blackBorderPixels;
            const sourceWidth = img.naturalWidth - blackBorderPixels;

            // Preserve source aspect ratio and cover the canvas (avoid stretched/blurry frames).
            const sourceAspect = sourceWidth / img.naturalHeight;
            const canvasAspect = w / h;
            let sx = sourceX;
            let sy = 0;
            let sw = sourceWidth;
            let sh = img.naturalHeight;

            if (canvasAspect > sourceAspect) {
                // Canvas is wider: crop top/bottom from source.
                sh = Math.max(1, sourceWidth / canvasAspect);
                sy = Math.max(0, (img.naturalHeight - sh) / 2);
            } else {
                // Canvas is taller: crop left/right from source.
                sw = Math.max(1, img.naturalHeight * canvasAspect);
                sx = Math.max(sourceX, sourceX + (sourceWidth - sw) / 2);
            }

            ctx.drawImage(img, sx, sy, sw, sh, 0, 0, w, h);
            this.lastFrameDrawAt = performance.now();
        }

        ensureHeroVisible() {
            if (!this.contentShown || this.loadError || !this.stickyEl) return;
            if (!this.isInView()) return;
            if (this.stickyEl.style.display === 'none') {
                this.showSticky();
            }
            this.setupCanvas();
            const frameCount = this.frames.length;
            if (frameCount > 0) {
                const frameIndex = this.lastFrameIndex >= 0
                    ? this.lastFrameIndex
                    : Math.floor(this.progress * (frameCount - 1));
                this.drawFrame(frameIndex);
            }
        }

        updateOverlays() {
            // Throttle overlay updates to reduce DOM manipulation overhead
            const now = performance.now();
            if (now - this.lastOverlayUpdate < 16) return; // ~60fps max
            this.lastOverlayUpdate = now;

            const p = this.progress;

            // Copy 0: fade out at 0-14%
            const opacity0 = p <= 0.14 ? 1 - (p / 0.14) : 0;
            if (this.copy0) this.copy0.style.opacity = opacity0;

            // Copy 30: fade in/out at 20-46%
            const opacity30 = p >= 0.2 && p <= 0.46 
                ? (p <= 0.36 ? (p - 0.2) / 0.16 : 1 - ((p - 0.36) / 0.1))
                : 0;
            if (this.copy30) this.copy30.style.opacity = opacity30;

            // Copy 60: fade in/out at 50-74%
            const opacity60 = p >= 0.5 && p <= 0.74
                ? (p <= 0.64 ? (p - 0.5) / 0.14 : 1 - ((p - 0.64) / 0.1))
                : 0;
            if (this.copy60) this.copy60.style.opacity = opacity60;

            // Copy 90: fade in at 80-94%
            const opacity90 = p >= 0.8 ? Math.min(1, (p - 0.8) / 0.14) : 0;
            if (this.copy90) this.copy90.style.opacity = opacity90;

            // Hero CTA: fade in at 75-90%
            const heroCtaOpacity = p >= 0.75 ? Math.min(1, (p - 0.75) / 0.15) : 0;
            if (this.heroCta) this.heroCta.style.opacity = heroCtaOpacity;

            // Dark overlay: fade in at 76-88%
            const darkOverlayOpacity = p >= 0.76 ? Math.min(1, (p - 0.76) / 0.12) : 0;
            if (this.darkOverlay) this.darkOverlay.style.opacity = darkOverlayOpacity;

            // Form overlay: fade in and slide up at 82-94%
            const formOpacity = p >= 0.82 ? Math.min(1, (p - 0.82) / 0.12) : 0;
            const formY = p >= 0.82 ? 24 * (1 - formOpacity) : 24;
            if (this.formOverlay) {
                this.formOverlay.style.opacity = formOpacity;
                this.formOverlay.style.transform = `translateY(${formY}px)`;
            }
        }

        animate() {
            // Faster, more responsive interpolation for smoother feel
            const smoothFactor = 0.25;
            this.progress = lerp(this.progress, this.targetProgress, smoothFactor);

            // Map progress to frame index
            const frameCount = this.frames.length;
            if (frameCount > 0) {
                const frameIndex = this.progress * (frameCount - 1);
                const currentFrame = Math.floor(frameIndex);
                
                // Only redraw if frame changed (reduces unnecessary canvas operations)
                if (currentFrame !== this.lastFrameIndex) {
                    this.lastFrameIndex = currentFrame;
                    this.drawFrame(frameIndex);
                }
                // Keep-alive repaint if a browser drops canvas content while frame is unchanged.
                const now = performance.now();
                if (this.isInView() && now - this.lastFrameDrawAt > 1200) {
                    this.drawFrame(this.lastFrameIndex >= 0 ? this.lastFrameIndex : currentFrame);
                }
            }

            // Update overlays (throttled internally)
            this.updateOverlays();
            
            // Throttle progress dispatch to reduce event overhead
            if (Math.abs(this.progress - (this.lastDispatchedProgress || 0)) > 0.01) {
                this.dispatchProgress();
                this.lastDispatchedProgress = this.progress;
            }

            this.rafId = requestAnimationFrame(() => this.animate());
        }

        destroy() {
            if (this.rafId) cancelAnimationFrame(this.rafId);
        }
    }

    // Initialize when DOM is ready with better error handling
    function initAirplaneScroll() {
        const container = document.getElementById('airplane-scroll');
        if (!container) {
            // Section doesn't exist on this page - exit silently
            return;
        }

        // Wait for config with timeout
        let configReady = false;
        let attempts = 0;
        const maxAttempts = 10; // Check 10 times (up to 1 second)
        
        const checkConfig = () => {
            attempts++;
            
            if (typeof airplaneScrollConfig !== 'undefined' && airplaneScrollConfig.framesFolder) {
                configReady = true;
                try {
                    window.airplaneScrollInstance = new AirplaneScroll(airplaneScrollConfig);
                } catch (error) {
                    console.error('AirplaneScroll: Initialization error', error);
                    const errorEl = document.getElementById('airplane-scroll-error');
                    if (errorEl) {
                        errorEl.style.display = 'flex';
                        const loadingEl = document.getElementById('airplane-scroll-loading');
                        if (loadingEl) loadingEl.style.display = 'none';
                    }
                }
                return;
            }
            
            // Retry if config not ready yet
            if (attempts < maxAttempts) {
                setTimeout(checkConfig, 100);
            } else {
                // Config not available after timeout - use fallback
                console.warn('AirplaneScroll: Config not available, using fallback');
                const fallbackConfig = {
                    frameCount: 93,
                    framesFolder: window.location.origin + '/wp-content/themes/ark-travelers/assets/images/airplane zip/',
                    mobileFrameCount: 240,
                    mobileFramesFolder: window.location.origin + '/wp-content/themes/ark-travelers/assets/images/airplane mobile zip/',
                };
                try {
                    window.airplaneScrollInstance = new AirplaneScroll(fallbackConfig);
                } catch (error) {
                    console.error('AirplaneScroll: Fallback initialization error', error);
                    const errorEl = document.getElementById('airplane-scroll-error');
                    if (errorEl) {
                        errorEl.style.display = 'flex';
                        const loadingEl = document.getElementById('airplane-scroll-loading');
                        if (loadingEl) loadingEl.style.display = 'none';
                    }
                }
            }
        };
        
        // Start checking config
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkConfig);
        } else {
            // DOM already ready
            checkConfig();
        }
    }
    
    // Initialize
    initAirplaneScroll();
})();
