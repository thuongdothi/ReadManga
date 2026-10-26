<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = [
    '/' => 'Home',
    '/truyen' => 'Comics Catalog',
    '/truyen/solo-leveling-toi-thang-cap-mot-minh' => 'Comic Detail',
    '/truyen/solo-leveling-toi-thang-cap-mot-minh/chapter-1' => 'Comic Reader',
    '/shop' => 'Manga Shop',
    '/shop/san-pham/combo-boxset-solo-leveling-tap-1-8' => 'Product Detail',
    '/gio-hang' => 'Cart View',
    '/vi-xu' => 'Wallet / Coin Topup',
    '/dang-nhap' => 'Login Form',
    '/dang-ky' => 'Register Form',
    '/api/search?q=solo' => 'Live Search API'
];

echo "Testing registered routes...\n";
$allPass = true;
foreach ($routes as $uri => $label) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    echo "  [$status] $label: $uri\n";
    if ($status !== 200 && $status !== 302) {
        $allPass = false;
    }
    $kernel->terminate($request, $response);
}

if ($allPass) {
    echo "\n>>> ALL 11 TESTED ROUTES RETURNED 200/302 OK! <<<\n";
} else {
    echo "\n>>> SOME ROUTES FAILED <<<\n";
}
