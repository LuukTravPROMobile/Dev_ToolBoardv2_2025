<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application for console use
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "\n--- SHOW CREATE TABLE users ---\n";
try {
    $res = DB::select("SHOW CREATE TABLE users");
    print_r($res[0]);
} catch (\Exception $e) {
    echo "Error SHOW CREATE TABLE users: " . $e->getMessage() . "\n";
}

echo "\n--- SHOW CREATE TABLE cache ---\n";
try {
    $res = DB::select("SHOW CREATE TABLE cache");
    print_r($res[0]);
} catch (\Exception $e) {
    echo "Error SHOW CREATE TABLE cache: " . $e->getMessage() . "\n";
}

// Seed sample users if none exist
$userCount = DB::table('users')->count();
if ($userCount === 0) {
    echo "\nSeeding sample users...\n";
    $now = \Carbon\Carbon::now();
    $users = [];
    for ($i = 0; $i < 12; $i++) {
        // create one graduate per month for last 12 months with varying counts
        $date = $now->copy()->subMonths($i);
        $num = ($i % 4) + 1; // 1..4 users per month
        for ($j = 0; $j < $num; $j++) {
            $users[] = [
                'name' => "Graduate_{$i}_{$j}",
                'email' => "graduate_{$i}_{$j}@$i.example",
                'password' => Hash::make('password'),
                'role' => 'graduate',
                'is_admin' => 0,
                'created_at' => $date->toDateTimeString(),
                'updated_at' => $date->toDateTimeString(),
            ];
        }
    }
    DB::table('users')->insert($users);
    echo "Inserted " . count($users) . " users.\n";
} else {
    echo "\nUsers already present: $userCount\n";
}

// Show counts
echo "\n--- counts after seeding ---\n";
echo "users: " . DB::table('users')->count() . "\n";
echo "cache: " . (DB::table('cache')->count() ?? 0) . "\n";

// Call analytics controller method directly
echo "\n--- Calling AnalyticsController::graduates() ---\n";
try {
    $request = Illuminate\Http\Request::create('/api/v1/analytics/graduates', 'GET', ['role' => 'graduate']);
    $controller = app()->make(App\Http\Controllers\Api\AnalyticsController::class);
    $response = $controller->graduates($request);
    if ($response instanceof Illuminate\Http\JsonResponse) {
        echo $response->getContent() . "\n";
    } else {
        print_r($response);
    }
} catch (\Exception $e) {
    echo "Error calling analytics: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
