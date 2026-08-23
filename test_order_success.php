<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::latest('id')->first();
$encoded = encodeOrderId($order->order_id);

$request = Illuminate\Http\Request::create('/order-success?order=' . urlencode($encoded), 'GET');
$request->setLaravelSession($app['session.store']);

try {
    $response = $kernel->handle($request);
    echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
    if ($response->getStatusCode() >= 400) {
        echo substr(strip_tags($response->getContent()), 0, 400) . PHP_EOL;
    } else {
        echo 'OK - order success page rendered' . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}
