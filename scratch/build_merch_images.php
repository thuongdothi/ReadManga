<?php

// 1. Download Chapter pages from Pepper & Carrot
$chapterDir = __DIR__ . '/../public/uploads/chapters';
if (!is_dir($chapterDir)) {
    mkdir($chapterDir, 0777, true);
}

$productDir = __DIR__ . '/../public/uploads/products';
if (!is_dir($productDir)) {
    mkdir($productDir, 0777, true);
}

$coverDir = __DIR__ . '/../public/uploads/covers';

$chapterPages = [
    'ch1_page_01.jpg' => 'https://www.peppercarrot.com/0_sources/ep01_Potion-of-Flight/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E01P00.jpg',
    'ch1_page_02.jpg' => 'https://www.peppercarrot.com/0_sources/ep01_Potion-of-Flight/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E01P01.jpg',
    'ch1_page_03.jpg' => 'https://www.peppercarrot.com/0_sources/ep01_Potion-of-Flight/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E01P02.jpg',
    'ch1_page_04.jpg' => 'https://www.peppercarrot.com/0_sources/ep01_Potion-of-Flight/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E01P03.jpg',
    'ch1_page_05.jpg' => 'https://www.peppercarrot.com/0_sources/ep01_Potion-of-Flight/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E01P04.jpg',
    
    'ch2_page_01.jpg' => 'https://www.peppercarrot.com/0_sources/ep06_The-Potion-Contest/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E06P01.jpg',
    'ch2_page_02.jpg' => 'https://www.peppercarrot.com/0_sources/ep06_The-Potion-Contest/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E06P02.jpg',
    'ch2_page_03.jpg' => 'https://www.peppercarrot.com/0_sources/ep06_The-Potion-Contest/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E06P03.jpg',
    'ch2_page_04.jpg' => 'https://www.peppercarrot.com/0_sources/ep06_The-Potion-Contest/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E06P04.jpg',
    'ch2_page_05.jpg' => 'https://www.peppercarrot.com/0_sources/ep06_The-Potion-Contest/low-res/vi_Pepper-and-Carrot_by-David-Revoy_E06P05.jpg',
];

echo "Downloading comic chapter pages...\n";
foreach ($chapterPages as $file => $url) {
    $dest = $chapterDir . '/' . $file;
    if (!file_exists($dest) || filesize($dest) < 1000) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code == 200 && strlen($data) > 1000) {
            file_put_contents($dest, $data);
            echo "Downloaded: $file (" . strlen($data) . " bytes)\n";
        } else {
            echo "Failed $file: HTTP $code\n";
        }
    } else {
        echo "Exists: $file\n";
    }
}

// 2. Generate / Build Authentic Merchandise Images
$products = [
    [
        'file' => 'solo-leveling-boxset.jpg',
        'cover' => 'solo-leveling.jpg',
        'title' => 'SOLO LEVELING',
        'subtitle' => 'PREMIUM MANHWA BOXSET (VOL 1-8)',
        'tag' => 'LIMITED COLLECTOR EDITION',
        'bg_start' => [15, 23, 42],
        'bg_end' => [30, 41, 59],
        'badge' => 'FULL 8 TẬP + POSTCARD SET',
        'price_tag' => '950.000 đ'
    ],
    [
        'file' => 'luffy-gear-5.jpg',
        'cover' => 'one-piece.jpg',
        'title' => 'MONKEY D. LUFFY',
        'subtitle' => 'GEAR 5 SUN GOD NIKA - KING OF ARTIST',
        'tag' => 'BANDAI SPIRITS OFFICIAL',
        'bg_start' => [40, 20, 10],
        'bg_end' => [80, 35, 15],
        'badge' => 'SCALE 1/8 • 23CM PVC',
        'price_tag' => '1.450.000 đ'
    ],
    [
        'file' => 'chainsaw-man-pochita-shirt.jpg',
        'cover' => 'chainsaw-man.jpg',
        'title' => 'CHAINSAW MAN x POCHITA',
        'subtitle' => 'OVERSIZED HEAVYWEIGHT TEE 260GSM',
        'tag' => 'OFFICIAL STREETWEAR APPAREL',
        'bg_start' => [24, 24, 27],
        'bg_end' => [63, 63, 70],
        'badge' => '100% COTTON COMPACT 2-WAY',
        'price_tag' => '320.000 đ'
    ],
    [
        'file' => 'spy-family-anya-bookmark.jpg',
        'cover' => 'spy-x-family.jpg',
        'title' => 'SPY x FAMILY - ANYA & BOND',
        'subtitle' => 'HOLLOW METAL BOOKMARK & ENAMEL CHARM',
        'tag' => 'OFFICIAL MERCHANDISE',
        'bg_start' => [30, 15, 25],
        'bg_end' => [60, 25, 45],
        'badge' => 'MẠ VÀNG 18K • DÂY ĐEO CHUÔNG',
        'price_tag' => '85.000 đ'
    ],
    [
        'file' => 'frieren-nendoroid.jpg',
        'cover' => 'frieren.jpg',
        'title' => 'NENDOROID #2354 FRIEREN',
        'subtitle' => 'BEYOND JOURNEY\'S END - GOOD SMILE',
        'tag' => 'GOOD SMILE COMPANY JAPAN',
        'bg_start' => [15, 30, 40],
        'bg_end' => [25, 55, 70],
        'badge' => 'FULL TRƯỢNG PHÁP THUẬT & 3 BIỂU CẢM',
        'price_tag' => '1.250.000 đ'
    ],
    [
        'file' => 'gojo-satoru-figure.jpg',
        'cover' => 'jujutsu-kaisen.jpg',
        'title' => 'GOJO SATORU - VÔ LƯỢNG KHÔNG XỨ',
        'subtitle' => 'S.H.FIGUARTS SPECIAL DOMAIN EXPANSION',
        'tag' => 'TAMASHII NATIONS',
        'bg_start' => [20, 10, 40],
        'bg_end' => [45, 15, 80],
        'badge' => 'HIỆU ỨNG VÔ LƯỢNG KÈM HEAD BỎ BĂNG MẮT',
        'price_tag' => '1.850.000 đ'
    ]
];

echo "Generating authentic merchandise images...\n";

foreach ($products as $p) {
    $w = 800;
    $h = 800;
    $im = imagecreatetruecolor($w, $h);
    
    // Draw gradient background
    for ($y = 0; $y < $h; $y++) {
        $ratio = $y / $h;
        $r = (int)($p['bg_start'][0] + ($p['bg_end'][0] - $p['bg_start'][0]) * $ratio);
        $g = (int)($p['bg_start'][1] + ($p['bg_end'][1] - $p['bg_start'][1]) * $ratio);
        $b = (int)($p['bg_start'][2] + ($p['bg_end'][2] - $p['bg_start'][2]) * $ratio);
        $color = imagecolorallocate($im, $r, $g, $b);
        imageline($im, 0, $y, $w, $y, $color);
    }
    
    // Ambient circular glow in center
    $glowColor = imagecolorallocatealpha($im, 255, 255, 255, 115);
    imagefilledellipse($im, 400, 360, 480, 480, $glowColor);
    
    // Draw card container backdrop
    $cardBg = imagecolorallocatealpha($im, 15, 20, 30, 40);
    imagefilledrectangle($im, 40, 40, $w - 40, $h - 40, $cardBg);
    $border = imagecolorallocatealpha($im, 255, 255, 255, 95);
    imagerectangle($im, 40, 40, $w - 40, $h - 40, $border);
    
    // Top banner tag
    $tagBg = imagecolorallocate($im, 99, 102, 241);
    imagefilledrectangle($im, 60, 60, 360, 95, $tagBg);
    $white = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, 4, 75, 70, $p['tag'], $white);
    
    // Load and paste comic cover artwork
    $coverPath = $coverDir . '/' . $p['cover'];
    if (file_exists($coverPath)) {
        $coverIm = imagecreatefromjpeg($coverPath);
        if ($coverIm) {
            $cw = imagesx($coverIm);
            $ch = imagesy($coverIm);
            
            // Draw a realistic 3D shadow behind cover
            $shadowColor = imagecolorallocatealpha($im, 0, 0, 0, 70);
            imagefilledrectangle($im, 275, 140, 565, 540, $shadowColor);
            
            // Resize and copy cover
            $destW = 260;
            $destH = 370;
            $destX = 270;
            $destY = 130;
            
            imagecopyresampled($im, $coverIm, $destX, $destY, 0, 0, $destW, $destH, $cw, $ch);
            
            // Border around cover
            $coverBorder = imagecolorallocate($im, 255, 255, 255);
            imagerectangle($im, $destX, $destY, $destX + $destW, $destY + $destH, $coverBorder);
            
            imagedestroy($coverIm);
        }
    }
    
    // Badge pill at bottom of image
    $badgeBg = imagecolorallocate($im, 245, 158, 11); // Amber
    $black = imagecolorallocate($im, 15, 23, 42);
    imagefilledrectangle($im, 220, 520, 580, 555, $badgeBg);
    imagestring($im, 4, 235, 530, $p['badge'], $black);
    
    // Bottom details container
    $bottomBg = imagecolorallocatealpha($im, 10, 15, 26, 30);
    imagefilledrectangle($im, 40, 580, $w - 40, $h - 40, $bottomBg);
    
    // Title
    $titleColor = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, 5, 60, 610, $p['title'], $titleColor);
    
    // Subtitle
    $subColor = imagecolorallocate($im, 203, 213, 225);
    imagestring($im, 4, 60, 645, $p['subtitle'], $subColor);
    
    // Price tag highlight
    $priceBg = imagecolorallocate($im, 16, 185, 129); // Emerald
    imagefilledrectangle($im, 60, 690, 240, 740, $priceBg);
    imagestring($im, 5, 75, 705, $p['price_tag'], $white);
    
    // Authenticity seal
    $gold = imagecolorallocate($im, 251, 191, 36);
    imagestring($im, 4, 520, 705, "100% GENUINE JAPAN", $gold);
    
    // Save to product directory
    $outPath = $productDir . '/' . $p['file'];
    imagejpeg($im, $outPath, 92);
    imagedestroy($im);
    echo "Generated product: {$p['file']} (" . filesize($outPath) . " bytes)\n";
}

echo "All assets ready!\n";
