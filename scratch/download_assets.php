<?php

function search_commons($query) {
    $url = "https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($query) . "&srnamespace=6&format=json";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'ReadMangaBot/1.0 (contact: student@vku.udn.vn)');
    $json = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($json, true);
    return $data['query']['search'] ?? [];
}

$queries = ['Luffy figure', 'anime figure', 'nendoroid', 'cosplay anime convention', 'manga volume'];
foreach ($queries as $q) {
    echo "=== $q ===\n";
    $results = search_commons($q);
    for ($i = 0; $i < min(2, count($results)); $i++) {
        echo "  - " . $results[$i]['title'] . "\n";
    }
}
