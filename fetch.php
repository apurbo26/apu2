<?php
// ১. আপনার মেইন M3U লিংকগুলোর লিস্ট নিচে বসান
$m3u_urls = [
    'g',
    'h',
    'https://raw.githubusercontent.com/srhady/willow-event/refs/heads/main/primevideo_sports.m3u'
];

$output_file = 'playlist.m3u';
$combined_content = "#EXTM3U\n";

// cURL ব্যবহার করে ডাটা আনার ফাংশন
function fetch_m3u($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// প্রতিটি লিংক থেকে ডাটা ডাউনলোড ও মার্জ করা
foreach ($m3u_urls as $url) {
    $content = fetch_m3u($url);
    
    if (!empty($content)) {
        // লাইনের ভেতরের স্পেস বা এন্টার ঠিক করা
        $lines = preg_split('/\r\n|\r|\n/', $content);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            // মূল ফাইলের #EXTM3U বাদ দিয়ে শুধু চ্যানেলের তথ্য যোগ করা
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
