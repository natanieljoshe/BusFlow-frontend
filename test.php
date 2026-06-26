<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$response = Illuminate\Support\Facades\Http::post('http://127.0.0.1:8010/api/login', [
    'email' => 'admin@busflow.com',
    'password' => 'password123',
]);

echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
