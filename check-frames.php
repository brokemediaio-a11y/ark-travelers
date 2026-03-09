<?php
/**
 * Frame Diagnostic Tool
 * Check which frames exist and which are missing
 * Access: https://your-site.com/wp-content/themes/YOUR-THEME-FOLDER/check-frames.php
 */

// Load WordPress
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

// Frame configuration
$theme_dir = get_stylesheet_directory();
$theme_uri = get_stylesheet_directory_uri();
$frames_folder = $theme_dir . '/assets/images/airplane zip/';
$frames_uri = $theme_uri . '/assets/images/airplane zip/';
$frame_count = 93; // Updated to match airplane zip folder (93 frames)
$frame_pattern = 'ezgif-frame-%03d.jpg'; // ezgif-frame-001.jpg format (with zero padding)

echo "<!DOCTYPE html>";
echo "<html><head><title>Frame Diagnostic</title>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #0a0e14; color: white; }
    .exists { color: #4ade80; }
    .missing { color: #f87171; font-weight: bold; }
    .summary { background: #1e293b; padding: 20px; margin: 20px 0; border-radius: 8px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #334155; }
    th { background: #1e293b; position: sticky; top: 0; }
    code { background: rgba(0,0,0,0.3); padding: 2px 6px; border-radius: 3px; }
</style>";
echo "</head><body>";

echo "<h1>🔍 Frame Diagnostic Tool</h1>";
echo "<div class='summary'>";
echo "<h2>📁 Path Information</h2>";
echo "<p><strong>Theme Folder (Server):</strong> <code>" . esc_html(basename($theme_dir)) . "</code></p>";
echo "<p><strong>Theme URI:</strong> <code>" . esc_html($theme_uri) . "</code></p>";
echo "<p><strong>Frames Folder (Server Path):</strong> <code>" . esc_html($frames_folder) . "</code></p>";
echo "<p><strong>Frames URI (Web URL):</strong> <code>" . esc_html($frames_uri) . "</code></p>";
echo "</div>";

// Check if folder exists
if (!is_dir($frames_folder)) {
    echo "<p class='missing'>❌ ERROR: Folder does NOT exist!</p>";
    echo "<p>Expected path: " . esc_html($frames_folder) . "</p>";
    echo "<p>Please create this folder and upload your frames.</p>";
    echo "</body></html>";
    exit;
}

// Scan directory for ALL jpg files
$all_files = glob($frames_folder . '*.jpg');
$total_files = count($all_files);

echo "<div class='summary'>";
echo "<h2>📊 Summary</h2>";
echo "<p>Total JPG files found: <strong>$total_files</strong></p>";
echo "<p>Expected frames: <strong>$frame_count</strong></p>";

if ($total_files == 0) {
    echo "<p class='missing'>❌ NO FRAMES FOUND! Please upload your frames to this folder.</p>";
} elseif ($total_files < $frame_count) {
    echo "<p class='missing'>⚠️ Missing " . ($frame_count - $total_files) . " frames</p>";
} elseif ($total_files > $frame_count) {
    echo "<p style='color: #fbbf24;'>ℹ️ You have MORE frames than expected. Update frameCount to $total_files</p>";
} else {
    echo "<p class='exists'>✅ Perfect! All frames present.</p>";
}
echo "</div>";

// Check each expected frame
$missing_frames = [];
$existing_frames = [];

echo "<h2>Frame by Frame Check</h2>";
echo "<table>";
echo "<tr><th>Frame #</th><th>Expected Filename</th><th>Status</th><th>File Size</th></tr>";

for ($i = 1; $i <= $frame_count; $i++) {
    $frame_name = sprintf($frame_pattern, $i);
    $frame_path = $frames_folder . $frame_name;
    
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td><code>" . esc_html($frame_name) . "</code></td>";
    
    if (file_exists($frame_path)) {
        $file_size = filesize($frame_path);
        $size_kb = round($file_size / 1024, 2);
        echo "<td class='exists'>✅ EXISTS</td>";
        echo "<td>" . $size_kb . " KB</td>";
        $existing_frames[] = $i;
    } else {
        echo "<td class='missing'>❌ MISSING</td>";
        echo "<td>-</td>";
        $missing_frames[] = $i;
    }
    
    echo "</tr>";
}

echo "</table>";

// Show actual files in folder
echo "<h2>📁 Actual Files in Folder</h2>";
if ($total_files > 0) {
    echo "<p>Here are the actual files found:</p>";
    echo "<ul style='max-height: 400px; overflow-y: auto; background: #1e293b; padding: 20px; border-radius: 8px;'>";
    foreach ($all_files as $file) {
        $filename = basename($file);
        $size_kb = round(filesize($file) / 1024, 2);
        echo "<li><code>" . esc_html($filename) . "</code> ($size_kb KB)</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='missing'>No JPG files found in folder!</p>";
}

// Recommendations
echo "<div class='summary'>";
echo "<h2>💡 Recommendations</h2>";

if ($total_files == 0) {
    echo "<ol>";
    echo "<li><strong>Upload your frames</strong> to: <code>/assets/images/airplane zip/</code></li>";
    echo "<li>Frames must be named: <code>ezgif-frame-001.jpg, ezgif-frame-002.jpg, etc.</code></li>";
    echo "<li>Use 3-digit zero padding (001 not 1)</li>";
    echo "</ol>";
} elseif (count($missing_frames) > 0) {
    echo "<p class='missing'>Missing frames: " . esc_html(implode(', ', array_slice($missing_frames, 0, 20)));
    if (count($missing_frames) > 20) {
        echo " ... and " . (count($missing_frames) - 20) . " more";
    }
    echo "</p>";
    echo "<p>Please upload the missing frames or adjust frameCount to: <strong>" . count($existing_frames) . "</strong></p>";
} elseif ($total_files != $frame_count) {
    echo "<p>Update your JavaScript config:</p>";
    echo "<pre style='background: #000; padding: 15px; border-radius: 6px; overflow-x: auto;'>";
    echo "frameCount: $total_files, // Found $total_files frames</pre>";
} else {
    echo "<p class='exists'>✅ Everything looks good! Your frames are ready.</p>";
    echo "<p>Make sure your JavaScript has: <code>frameCount: $frame_count</code></p>";
}

echo "</div>";

// Generate corrected JavaScript config
echo "<div class='summary'>";
echo "<h2>🔧 Corrected JavaScript Config</h2>";
echo "<p>Copy this into your functions.php wp_localize_script:</p>";
echo "<pre style='background: #000; padding: 15px; border-radius: 6px; overflow-x: auto;'>";
echo "wp_localize_script( 'video-scrubbing', 'videoScrubConfig', array(\n";
echo "    'frameCount' => $total_files,\n";
echo "    'framesFolder' => '" . esc_html(str_replace(' ', '%20', $frames_uri)) . "/',\n"; // URL-encode space
echo "    'themeUrl' => '" . esc_html($theme_uri) . "',\n";
echo ") );</pre>";
echo "<p style='margin-top: 15px;'><strong>Test URL:</strong> <a href='" . esc_html($frames_uri . '/frame_001.jpg') . "' target='_blank' style='color: #4ade80;'>" . esc_html($frames_uri . '/frame_001.jpg') . "</a></p>";
echo "</div>";

echo "</body></html>";
?>
