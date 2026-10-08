<?php
// ১. আপনার মেইন M3U লিংকগুলোর লিস্ট নিচে বসান
$m3u_urls = [
    'https://alixbd.com/playlistconfig/playlist.m3u',
    'https://alixbd.com/playlistconfig/playlist.m3u',
    'https://is.gd/i9cTSF'
];

$output_file = 'playlist.m3u';
$combined_content = "#EXTM3U\n";

foreach ($m3u_urls as $url) {
    // URL থেকে ডাটা ডাউনলোড করার চেষ্টা
    $content = @file_get_contents($url);
    
    if ($content !== false) {
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            // #EXTM3U হেডার বাদ দিয়ে বাকি সব লাইন যোগ করবে
            if (!empty($trimmed) && strpos($trimmed, '#EXTM3U') === false) {
                $combined_content .= $trimmed . "\n";
            }
        }
    } else {
        echo "Failed to fetch: " . $url . "\n";
    }
}

// নতুন Combined M3U ফাইল তৈরি
file_put_contents($output_file, $combined_content);
echo "Successfully updated " . $output_file . "\n";
?>
