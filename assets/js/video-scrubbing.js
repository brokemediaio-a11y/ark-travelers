/**
 * Video Scrubbing Animation - FIXED VERSION
 * Handles 200-230 frames with better error handling
 * Hero content fades in when animation completes on last frame
 */

class VideoScrubbing {
    constructor(config = {}) {
        // Configuration
        this.config = {
            frameCount: 93, // Updated to match airplane zip folder (93 frames)
            framePathTemplate: 'ezgif-frame-{index}.jpg', // Actual naming: ezgif-frame-001.jpg, ezgif-frame-002.jpg, etc. (with zero padding)
            framesFolder: '', // Will be set from WordPress localization
            minScale: 1.0,
            maxScale: 1.5,
            scrollMultiplier: 1.0,
            skipMissingFrames: true, // Continue even if some frames fail
            maxFailedFrames: 50, // Max frames allowed to fail before stopping
            ...config
        };
        
        // Get folder from WordPress
        if (typeof videoScrubConfig !== 'undefined') {
            this.config.framesFolder = videoScrubConfig.framesFolder;
            this.config.frameCount = videoScrubConfig.frameCount || this.config.frameCount;
        }
        
        console.log('🎬 Video Scrubbing Config:', this.config);
        
        // DOM Elements
        this.canvas = document.getElementById('videoCanvas');
        this.container = document.getElementById('videoScrubContainer');
        this.section = document.getElementById('hero-video-scrub');
        this.loadingScreen = document.getElementById('videoScrubLoading');
        this.progressBar = document.getElementById('loadingProgress');
        this.loadingPercentage = document.getElementById('loadingPercentage');
        this.loadingText = document.querySelector('.loading-text');
        this.scrollIndicator = document.getElementById('scrollIndicator');
        this.heroContentFinal = document.getElementById('heroContentFinal');
        
        if (!this.canvas || !this.container || !this.section) {
            console.error('❌ VideoScrubbing: Required elements not found');
            return;
        }
        
        // Canvas context
        this.ctx = this.canvas.getContext('2d');
        
        // Frame data
        this.frames = [];
        this.imagesLoaded = 0;
        this.imagesFailed = 0;
        this.currentFrame = 0;
        this.targetFrame = 0;
        this.validFrameIndices = []; // Track which frames loaded successfully
        this.isComplete = false;
        
        // Scroll data
        this.scrollY = 0;
        this.sectionTop = 0;
        this.sectionHeight = 0;
        
        // Cached section values (prevents shrinking bug)
        this.cachedSectionHeight = 0;
        this.cachedSectionTop = 0;
        
        // Animation
        this.isAnimating = false;
        this.rafId = null;
        
        // Initialize
        this.init();
    }
    
    init() {
        console.log('🎬 Initializing Video Scrubbing...');
        
        // Force section height to 300vh for 93-frame coverage while keeping
        // the gap before the next section reasonable.
        const targetHeight = window.innerHeight * 3; // 300vh = 3 * viewport height
        this.section.style.height = targetHeight + 'px';
        this.section.style.minHeight = targetHeight + 'px';
        this.section.style.maxHeight = targetHeight + 'px'; // Lock it
        
        // Cache these values so they don't change during scroll
        this.cachedSectionHeight = targetHeight;
        this.cachedSectionTop = this.section.offsetTop;
        
        console.log(`📏 LOCKED section height: ${targetHeight}px (300vh)`);
        console.log(`📍 Section will scroll through ${this.cachedSectionHeight - window.innerHeight}px`);
        
        // FORCE STICKY CONTAINER (in case CSS doesn't load - prevents white space)
        if (this.container) {
            this.container.style.position = 'sticky';
            this.container.style.top = '0';
            this.container.style.height = '100vh';
            this.container.style.background = '#0a0e14';
            this.container.style.zIndex = '1';
            console.log('📌 Forced sticky container with black background');
        }
        
        // Set canvas size and initialize background
        this.resizeCanvas();
        
        // Ensure canvas has background color immediately
        const ctx = this.canvas.getContext('2d');
        if (ctx) {
            ctx.fillStyle = '#0a0e14';
            ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        }
        
        // Load frames
        this.preloadFrames();
        
        // Setup event listeners
        this.setupEventListeners();
    }
    
    preloadFrames() {
        console.log(`📦 Loading ${this.config.frameCount} frames from airplane zip folder...`);
        console.log(`📁 Folder: ${this.config.framesFolder}`);
        
        for (let i = 1; i <= this.config.frameCount; i++) {
            const img = new Image();
            // Use 3-digit zero padding: ezgif-frame-001.jpg, ezgif-frame-002.jpg, etc.
            const frameNumber = String(i).padStart(3, '0');
            // Ensure framesFolder ends with /
            const framesFolder = this.config.framesFolder.endsWith('/') 
                ? this.config.framesFolder 
                : this.config.framesFolder + '/';
            const framePath = framesFolder + 
                             this.config.framePathTemplate.replace('{index}', frameNumber);
            
            // Store the index for this image
            const frameIndex = i - 1;
            
            img.onload = () => {
                this.imagesLoaded++;
                this.validFrameIndices.push(frameIndex);
                this.updateLoadingProgress();
                
                // ✅ Draw first frame immediately when it loads (for instant display)
                if (frameIndex === 0 && this.canvas && this.ctx) {
                    this.currentFrame = 0;
                    this.targetFrame = 0;
                    this.resizeCanvas();
                    this.drawFrame(0, this.config.minScale);
                    console.log('🎬 First frame (airplane window) displayed immediately');
                }
                
                // Log every 10th frame
                if (i % 10 === 0) {
                    console.log(`✅ Loaded ${this.imagesLoaded}/${this.config.frameCount} frames`);
                }
                
                // Check if all frames processed
                if (this.imagesLoaded + this.imagesFailed >= this.config.frameCount) {
                    this.onFramesLoaded();
                }
            };
            
            img.onerror = () => {
                this.imagesFailed++;
                // Log first few failures with full path for debugging
                if (this.imagesFailed <= 3) {
                    console.error(`❌ Failed to load frame ${i}: ${framePath}`);
                    console.log(`   Full URL attempted: ${img.src}`);
                }
                
                // Update progress anyway
                this.updateLoadingProgress();
                
                // Check if too many failures
                if (this.imagesFailed > this.config.maxFailedFrames && !this.config.skipMissingFrames) {
                    console.error(`❌ Too many frames failed to load (${this.imagesFailed}). Stopping.`);
                    console.error(`   Expected folder: ${this.config.framesFolder}`);
                    console.error(`   Check: Are frames uploaded to the correct location?`);
                    this.showError();
                    return;
                }
                
                // Check if all frames processed
                if (this.imagesLoaded + this.imagesFailed >= this.config.frameCount) {
                    this.onFramesLoaded();
                }
            };
            
            img.src = framePath;
            this.frames[frameIndex] = img;
        }
    }
    
    updateLoadingProgress() {
        const totalProcessed = this.imagesLoaded + this.imagesFailed;
        const progress = (totalProcessed / this.config.frameCount) * 100;
        
        if (this.progressBar) {
            this.progressBar.style.width = progress + '%';
        }
        
        if (this.loadingPercentage) {
            this.loadingPercentage.textContent = Math.round(progress) + '%';
        }
        
        // Update loading text with stats
        if (this.loadingText && this.imagesFailed > 0) {
            this.loadingText.textContent = `Loading... (${this.imagesLoaded} loaded, ${this.imagesFailed} failed)`;
        }
    }
    
    onFramesLoaded() {
        console.log(`✅ All frames processed!`);
        console.log(`   Loaded: ${this.imagesLoaded} / ${this.config.frameCount}`);
        console.log(`   Failed: ${this.imagesFailed}`);
        
        // Check if we have enough frames to work with
        if (this.imagesLoaded < 10) {
            console.error('❌ Not enough frames loaded. Cannot start animation.');
            this.showError();
            return;
        }
        
        // Sort valid frame indices
        this.validFrameIndices.sort((a, b) => a - b);
        console.log(`✅ ${this.validFrameIndices.length} valid frames ready for animation`);
        console.log(`📊 Animation will use all ${this.config.frameCount} frame positions (missing frames will use nearest valid frame)`);
        
        // Log missing frames if significant number failed
        if (this.imagesFailed > 0 && this.imagesFailed < 50) {
            const missingFrames = [];
            for (let i = 0; i < this.config.frameCount; i++) {
                if (!this.validFrameIndices.includes(i)) {
                    missingFrames.push(i + 1);
                }
            }
            if (missingFrames.length > 0 && missingFrames.length <= 20) {
                console.log(`⚠️ Missing frames: ${missingFrames.join(', ')}`);
            } else if (missingFrames.length > 20) {
                console.log(`⚠️ Missing ${missingFrames.length} frames (first 20: ${missingFrames.slice(0, 20).join(', ')}...)`);
            }
        }
        
        // Hide loading screen
        setTimeout(() => {
            if (this.loadingScreen) {
                this.loadingScreen.classList.add('hidden');
            }
        }, 500);
        
        // ✅ START WITH FRAME 0 (first frame - airplane window)
        // Find the nearest valid frame to 0
        let startFrame = 0;
        if (this.validFrameIndices.length > 0) {
            // Find closest valid frame to 0
            startFrame = this.validFrameIndices[0];
            for (let i = 0; i < this.validFrameIndices.length; i++) {
                if (this.validFrameIndices[i] === 0) {
                    startFrame = 0;
                    break;
                }
                if (this.validFrameIndices[i] > 0) break;
            }
        }
        
        this.currentFrame = startFrame;
        this.targetFrame = startFrame;
        
        // Ensure canvas is ready before drawing
        this.resizeCanvas();
        // Draw first frame with minimum scale (window view)
        this.drawFrame(startFrame, this.config.minScale);
        
        console.log(`🎬 Starting animation with frame ${startFrame + 1}/${this.config.frameCount} (airplane window view)`);
        
        // Start animation loop
        this.startAnimation();
    }
    
    showError() {
        if (this.loadingText) {
            this.loadingText.textContent = '❌ Failed to load frames';
            this.loadingText.style.color = '#f87171';
        }
        
        // Show error message with actual path being used
        const errorMsg = document.createElement('div');
        errorMsg.style.cssText = 'margin-top: 20px; padding: 20px; background: rgba(248,113,113,0.1); border: 1px solid #f87171; border-radius: 8px; max-width: 500px;';
        errorMsg.innerHTML = `
            <p style="color: #f87171; margin: 0 0 10px 0; font-weight: 600;">Frames Not Found</p>
            <p style="color: #fff; font-size: 14px; margin: 0 0 10px 0; line-height: 1.6;">
                Please check:<br>
                1. Frames are uploaded to <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">/assets/images/envatoplane/</code><br>
                2. Files named <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">frame_001.jpg</code> to <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">frame_236.jpg</code><br>
                3. Frame count matches actual files (expected: 236)
            </p>
            <p style="color: #a4b1b9; font-size: 12px; margin: 10px 0 0 0; font-family: monospace; word-break: break-all;">
                Path being used: ${this.config.framesFolder}
            </p>
            <p style="color: #a4b1b9; font-size: 12px; margin: 5px 0 0 0;">
                <strong>Tip:</strong> Use <code style="background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px;">check-frames.php</code> to verify frames are in the correct location.
            </p>
        `;
        
        if (this.loadingScreen) {
            const loadingContent = this.loadingScreen.querySelector('.loading-content');
            if (loadingContent) {
                loadingContent.appendChild(errorMsg);
            }
        }
    }
    
    setupEventListeners() {
        // Scroll event with prevention when animation is active
        let scrollTimeout;
        const scrollHandler = () => {
            if (scrollTimeout) clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => this.updateScrollPosition(), 10);
            this.updateScrollPosition();
        };
        window.addEventListener('scroll', scrollHandler, { passive: true });
        
        // Prevent scroll when animation is active (progress < 1)
        const wheelHandler = (e) => {
            if (!this.cachedSectionHeight || !this.cachedSectionTop) return;
            
            const scrollRange = this.cachedSectionHeight - window.innerHeight;
            const maxScroll = this.cachedSectionTop + scrollRange;
            const scrollProgress = this.getCurrentScrollProgress();
            
            // If animation not complete (progress < 0.995) and scrolling down
            if (scrollProgress < 0.995 && e.deltaY > 0) {
                // Prevent scroll beyond animation range
                const newScrollY = this.scrollY + e.deltaY;
                if (newScrollY > maxScroll) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Force scroll to max position
                    requestAnimationFrame(() => {
                        window.scrollTo({ top: maxScroll, behavior: 'auto' });
                    });
                    return false;
                }
            }
            
            // If scrolling up from beyond section, allow it
            if (e.deltaY < 0 && this.scrollY > maxScroll + 50) {
                return; // Allow normal scroll up when well past section
            }
            
            // If scrolling up within section, allow reverse animation
            if (e.deltaY < 0 && scrollProgress > 0) {
                return; // Allow scroll up for reverse animation
            }
        };
        window.addEventListener('wheel', wheelHandler, { passive: false });
        
        // Resize event - only update canvas, don't change cached section height
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                // Re-apply section height on resize (300vh) but keep cached values stable
                const targetHeight = window.innerHeight * 3;
                this.section.style.height = targetHeight + 'px';
                this.section.style.minHeight = targetHeight + 'px';
                this.section.style.maxHeight = targetHeight + 'px';
                
                // Only update cached height if it's significantly different (viewport changed)
                // But keep sectionTop cached to prevent scroll calculation issues
                if (Math.abs(this.cachedSectionHeight - targetHeight) > 100) {
                    this.cachedSectionHeight = targetHeight;
                }
                // Don't update cachedSectionTop on resize - keep it stable
                
                this.resizeCanvas();
                this.updateScrollPosition();
            }, 100);
        });
        
        this.updateScrollPosition();
    }
    
    resizeCanvas() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2); // Cap at 2x for performance
        const rect = this.container.getBoundingClientRect();
        
        // Ensure canvas fills container completely
        const width = Math.floor(rect.width);
        const height = Math.floor(rect.height);
        
        this.canvas.width = width * dpr;
        this.canvas.height = height * dpr;
        this.canvas.style.width = width + 'px';
        this.canvas.style.height = height + 'px';
        
        // Reset transform and scale
        this.ctx.setTransform(1, 0, 0, 1, 0, 0);
        this.ctx.scale(dpr, dpr);
        
        // Set background color
        this.ctx.fillStyle = '#0a0e14';
        this.ctx.fillRect(0, 0, width, height);
        
        if (this.frames.length > 0 && this.validFrameIndices.length > 0) {
            this.drawFrame(this.currentFrame, this.getCurrentScale());
        }
    }
    
    updateScrollPosition() {
        this.scrollY = window.scrollY || window.pageYOffset;
        
        // Use cached values (prevents the shrinking bug)
        // Only use cached if they're set, otherwise fall back to actual values
        if (this.cachedSectionHeight > 0 && this.cachedSectionTop > 0) {
            this.sectionTop = this.cachedSectionTop;
            this.sectionHeight = this.cachedSectionHeight;
        } else {
            // Fallback to actual values if cache not set yet
            this.sectionTop = this.section.offsetTop;
            this.sectionHeight = this.section.offsetHeight;
        }
        
        // Calculate scroll progress through the section (0 to 1)
        // For sticky elements: scroll range = sectionHeight - viewportHeight
        // This ensures when the bottom of section reaches viewport top, scrollProgress = 1
        const viewportHeight = window.innerHeight;
        const scrollRange = Math.max(1, this.sectionHeight - viewportHeight);
        
        // Calculate how far we've scrolled into the section
        const scrolledDistance = Math.max(0, this.scrollY - this.sectionTop);
        
        // Calculate scroll progress (0 to 1)
        let scrollProgress = scrollRange > 0 ? scrolledDistance / scrollRange : 0;
        
        // Clamp scrollProgress to 0-1 range
        scrollProgress = Math.max(0, Math.min(1, scrollProgress));
        
        // Map scroll progress to frame index (0 to 92, using ALL 93 frames)
        // Use Math.floor for smoother progression, then clamp to ensure we reach frame 93
        let targetFrameIndex = Math.floor(scrollProgress * (this.config.frameCount - 1));
        
        // Ensure we reach the last frame (index 92) when scrollProgress = 1.0
        if (scrollProgress >= 0.999) {
            targetFrameIndex = this.config.frameCount - 1; // Frame 93 (index 92)
        }
        
        // Clamp to valid range
        targetFrameIndex = Math.min(this.config.frameCount - 1, Math.max(0, targetFrameIndex));
        
        // ✅ Declare isAtLastFrame EARLY (before using it in logs)
        const isAtLastFrame = scrollProgress >= 0.97 || targetFrameIndex >= (this.config.frameCount - 1);
        
        // Find the nearest valid frame (in case some frames failed to load)
        if (this.validFrameIndices.length > 0) {
            // Find closest valid frame to targetFrameIndex
            let closestIndex = this.validFrameIndices[0];
            let minDiff = Math.abs(targetFrameIndex - closestIndex);
            
            for (let i = 0; i < this.validFrameIndices.length; i++) {
                const diff = Math.abs(targetFrameIndex - this.validFrameIndices[i]);
                if (diff < minDiff) {
                    minDiff = diff;
                    closestIndex = this.validFrameIndices[i];
                }
            }
            
            this.targetFrame = closestIndex;
        } else {
            this.targetFrame = targetFrameIndex;
        }
        
        // Reduced debug logging - only log at key milestones
        const shouldLog = (scrollProgress >= 0 && scrollProgress < 0.01) || 
                         (scrollProgress >= 0.49 && scrollProgress <= 0.51) || 
                         (scrollProgress >= 0.95) ||
                         (Math.abs(this.currentFrame - this.targetFrame) > 5);
        
        if (shouldLog && Math.random() < 0.1) { // Only log 10% of eligible events
            console.log(`📊 Progress: ${(scrollProgress * 100).toFixed(1)}% | Frame: ${this.currentFrame + 1}/${this.config.frameCount} → ${this.targetFrame + 1} | Scale: ${this.getCurrentScale().toFixed(2)}x`);
        }
        
        if (isAtLastFrame && !this.isComplete) {
            this.isComplete = true;
            this.showHeroContent();
        } else if (!isAtLastFrame && this.isComplete) {
            this.isComplete = false;
            this.hideHeroContent();
        }
        
        if (scrollProgress > 0.1) {
            this.container.classList.add('scrolled');
            if (this.scrollIndicator) this.scrollIndicator.classList.add('hidden');
        } else {
            this.container.classList.remove('scrolled');
            if (this.scrollIndicator) this.scrollIndicator.classList.remove('hidden');
        }
    }
    
    showHeroContent() {
        if (this.heroContentFinal) {
            setTimeout(() => {
                this.heroContentFinal.classList.add('visible');
            }, 300);
        }
    }
    
    hideHeroContent() {
        if (this.heroContentFinal) {
            this.heroContentFinal.classList.remove('visible');
        }
    }
    
    getCurrentScale() {
        // Calculate scale based on scroll progress
        // Start at minScale (1.0 - window view) and zoom to maxScale (1.5 - outside view)
        const viewportHeight = window.innerHeight;
        const scrollRange = Math.max(1, this.cachedSectionHeight - viewportHeight);
        const scrolledDistance = Math.max(0, this.scrollY - this.cachedSectionTop);
        const scrollProgress = scrollRange > 0 
            ? Math.max(0, Math.min(1, scrolledDistance / scrollRange))
            : 0;
        
        // Smooth scale interpolation from window (1.0) to outside view (1.5)
        const scale = this.config.minScale + (scrollProgress * (this.config.maxScale - this.config.minScale));
        return Math.max(this.config.minScale, Math.min(this.config.maxScale, scale));
    }
    
    startAnimation() {
        if (this.isAnimating) return;
        this.isAnimating = true;
        this.animate();
    }
    
    animate() {
        if (this.currentFrame !== this.targetFrame) {
            // ✅ Improved smooth frame interpolation
            // Calculate difference between current and target frame
            const diff = this.targetFrame - this.currentFrame;
            
            // Use smoother interpolation - smaller steps for gradual transition
            // This ensures all frames are shown smoothly without skipping
            const step = Math.sign(diff) * Math.max(1, Math.abs(diff) * 0.15); // Increased from 0.1 to 0.15 for smoother catch-up
            
            // Update current frame
            let newFrame = this.currentFrame + step;
            
            // Clamp to valid range
            if (diff > 0) {
                // Scrolling down - moving forward
                newFrame = Math.min(this.targetFrame, Math.max(this.currentFrame, newFrame));
            } else {
                // Scrolling up - moving backward
                newFrame = Math.max(this.targetFrame, Math.min(this.currentFrame, newFrame));
            }
            
            // Round to nearest integer frame
            this.currentFrame = Math.round(newFrame);
            
            // Ensure we're using a valid frame index
            if (this.validFrameIndices.length > 0) {
                // Find closest valid frame if currentFrame doesn't exist
                if (!this.frames[this.currentFrame] || !this.frames[this.currentFrame].complete) {
                    let closestValid = this.validFrameIndices[0];
                    let minDist = Math.abs(this.currentFrame - closestValid);
                    
                    for (let i = 0; i < this.validFrameIndices.length; i++) {
                        const dist = Math.abs(this.currentFrame - this.validFrameIndices[i]);
                        if (dist < minDist) {
                            minDist = dist;
                            closestValid = this.validFrameIndices[i];
                        }
                    }
                    this.currentFrame = closestValid;
                }
            }
            
            // Draw frame with current scale (zoom effect)
            this.drawFrame(this.currentFrame, this.getCurrentScale());
        } else {
            // Even when frames match, redraw to ensure scale updates smoothly
            this.drawFrame(this.currentFrame, this.getCurrentScale());
        }
        
        this.rafId = requestAnimationFrame(() => this.animate());
    }
    
    drawFrame(frameIndex, scale = 1.0) {
        // Validate frame exists and is loaded
        if (!this.frames[frameIndex]) {
            // Try to find nearest valid frame
            if (this.validFrameIndices.length > 0) {
                let closestValid = this.validFrameIndices[0];
                let minDist = Math.abs(frameIndex - closestValid);
                
                for (let i = 0; i < this.validFrameIndices.length; i++) {
                    const dist = Math.abs(frameIndex - this.validFrameIndices[i]);
                    if (dist < minDist) {
                        minDist = dist;
                        closestValid = this.validFrameIndices[i];
                    }
                }
                frameIndex = closestValid;
            } else {
                return; // No valid frames available
            }
        }
        
        const img = this.frames[frameIndex];
        
        // Check if image is loaded
        if (!img || !img.complete || !img.naturalWidth || img.naturalWidth === 0) {
            return; // Image not ready yet
        }
        
        const dpr = Math.min(window.devicePixelRatio || 1, 2); // Cap DPR at 2x
        const canvasWidth = this.canvas.width / dpr;
        const canvasHeight = this.canvas.height / dpr;
        
        // Fill canvas with background color first (prevents white gaps)
        this.ctx.fillStyle = '#0a0e14';
        this.ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        
        // Calculate image dimensions with zoom effect
        const imgAspect = img.naturalWidth / img.naturalHeight;
        const canvasAspect = canvasWidth / canvasHeight;
        
        let drawWidth, drawHeight;
        
        // Scale image based on aspect ratio and zoom
        if (imgAspect > canvasAspect) {
            // Image is wider - fit to height and scale width
            drawHeight = canvasHeight * scale;
            drawWidth = drawHeight * imgAspect;
        } else {
            // Image is taller - fit to width and scale height
            drawWidth = canvasWidth * scale;
            drawHeight = drawWidth / imgAspect;
        }
        
        // Center the image
        const x = (canvasWidth - drawWidth) / 2;
        const y = (canvasHeight - drawHeight) / 2;
        
        // Clear canvas and redraw
        this.ctx.clearRect(0, 0, canvasWidth, canvasHeight);
        
        // Fill background again (ensures no white gaps)
        this.ctx.fillStyle = '#0a0e14';
        this.ctx.fillRect(0, 0, canvasWidth, canvasHeight);
        
        // Draw image with zoom effect
        this.ctx.drawImage(img, x, y, drawWidth, drawHeight);
    }
    
    getCurrentScrollProgress() {
        const viewportHeight = window.innerHeight;
        const scrollRange = Math.max(1, this.cachedSectionHeight - viewportHeight);
        const scrolledDistance = this.scrollY - this.cachedSectionTop;
        return scrollRange > 0 
            ? Math.max(0, Math.min(1, scrolledDistance / scrollRange))
            : 0;
    }
    
    destroy() {
        if (this.rafId) cancelAnimationFrame(this.rafId);
        this.isAnimating = false;
        console.log('🛑 Video Scrubbing destroyed');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('hero-video-scrub')) {
        // Get config from localized script if available
        const config = window.videoScrubConfig || {};
        
        window.videoScrubInstance = new VideoScrubbing({
            frameCount: config.frameCount || 93, // Updated to match airplane zip folder (93 frames)
            framePathTemplate: 'ezgif-frame-{index}.jpg', // Actual naming: ezgif-frame-001.jpg (with zero padding)
            skipMissingFrames: true,
            maxFailedFrames: 10 // Allow some frames to fail
        });
    }
});
