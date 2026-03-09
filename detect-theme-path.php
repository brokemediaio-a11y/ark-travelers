<?php
/**
 * Theme Path Detection Tool
 * Run this to find your actual theme folder name
 * Access: https://your-site.com/wp-content/themes/YOUR-THEME-FOLDER/detect-theme-path.php
 */

// Load WordPress
$wp_load_paths = array(
    dirname( __FILE__ ) . '/../../../wp-load.php',
    dirname( __FILE__ ) . '/../../../../wp-load.php',
    dirname( __FILE__ ) . '/../../../../../wp-load.php',
);

$wp_loaded = false;
foreach ( $wp_load_paths as $path ) {
    if ( file_exists( $path ) ) {
        require_once( $path );
        $wp_loaded = true;
        break;
    }
}

if ( ! $wp_loaded ) {
    die( 'Could not load WordPress. Please access this file via WordPress URL.' );
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Theme Path Detection</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #0a0e14; color: white; }
        .info { background: #1e293b; padding: 15px; margin: 10px 0; border-radius: 8px; }
        code { background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px; color: #4ade80; }
        .success { color: #4ade80; }
        .error { color: #f87171; }
        .warning { color: #fbbf24; }
    </style>
</head>
<body>
    <h1>🔍 Theme Path Detection</h1>
    
    <div class="info">
        <h2>Current Theme Information</h2>
        <p><strong>Theme Name:</strong> <?php echo esc_html( wp_get_theme()->get( 'Name' ) ); ?></p>
        <p><strong>Theme Folder Name:</strong> <code><?php echo esc_html( basename( get_stylesheet_directory() ) ); ?></code></p>
        <p><strong>Stylesheet Directory:</strong> <code><?php echo esc_html( get_stylesheet_directory() ); ?></code></p>
        <p><strong>Stylesheet URI:</strong> <code><?php echo esc_html( get_stylesheet_directory_uri() ); ?></code></p>
    </div>
    
    <div class="info">
        <h2>Frames Folder Check</h2>
        <?php
$frames_folder = get_stylesheet_directory() . '/assets/images/airplane zip/';
$frames_uri = get_stylesheet_directory_uri() . '/assets/images/airplane zip/';
$test_frame = $frames_folder . 'ezgif-frame-001.jpg';
        $test_frame_uri = $frames_uri . 'frame_001.jpg';
        
        echo "<p><strong>Server Path:</strong> <code>" . esc_html( $frames_folder ) . "</code></p>";
        echo "<p><strong>Web URL:</strong> <code>" . esc_html( $frames_uri ) . "</code></p>";
        
        if ( is_dir( $frames_folder ) ) {
            echo "<p class='success'>✅ Folder exists on server</p>";
            $frame_count = count( glob( $frames_folder . '*.jpg' ) );
            echo "<p>Found <strong>$frame_count</strong> JPG files</p>";
        } else {
            echo "<p class='error'>❌ Folder does NOT exist: <code>" . esc_html( $frames_folder ) . "</code></p>";
        }
        
        if ( file_exists( $test_frame ) ) {
            echo "<p class='success'>✅ Test frame exists: <code>ezgif-frame-001.jpg</code></p>";
        } else {
            echo "<p class='error'>❌ Test frame NOT found: <code>ezgif-frame-001.jpg</code></p>";
        }
        ?>
    </div>
    
    <div class="info">
        <h2>Test Frame URL</h2>
        <p>Try accessing this URL directly:</p>
        <p><a href="<?php echo esc_url( $test_frame_uri ); ?>" target="_blank" style="color: #4ade80;"><?php echo esc_html( $test_frame_uri ); ?></a></p>
        <?php
        // Try to check if URL is accessible
        $headers = @get_headers( $test_frame_uri );
        if ( $headers && strpos( $headers[0], '200' ) !== false ) {
            echo "<p class='success'>✅ Frame is accessible via HTTP</p>";
        } else {
            echo "<p class='error'>❌ Frame is NOT accessible via HTTP (404 or other error)</p>";
            echo "<p class='warning'>⚠️ This means frames need to be uploaded to the server</p>";
        }
        ?>
    </div>
    
    <div class="info">
        <h2>Corrected Config for functions.php</h2>
        <pre style="background: #000; padding: 15px; border-radius: 6px; overflow-x: auto;">
wp_localize_script( 'video-scrubbing', 'videoScrubConfig', array(
    'frameCount' => 236,
    'framesFolder' => '<?php echo esc_js( $frames_uri ); ?>/',
    'themeUrl' => '<?php echo esc_js( get_stylesheet_directory_uri() ); ?>',
) );
        </pre>
    </div>
    
    <div class="info">
        <h2>Next Steps</h2>
        <ol>
            <li>If folder doesn't exist: Upload frames to <code><?php echo esc_html( $frames_folder ); ?></code></li>
            <li>If folder exists but frames missing: Upload all 236 frames</li>
            <li>If frames exist but URL 404: Check file permissions or .htaccess rules</li>
            <li>Copy the config above into your functions.php</li>
        </ol>
    </div>
</body>
</html>
