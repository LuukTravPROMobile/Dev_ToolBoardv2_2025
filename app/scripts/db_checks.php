<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the application for console use
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n--- tables in information_schema for dev_toolboard ---\n";
print_r(DB::select("SELECT table_schema, table_name FROM information_schema.tables WHERE table_schema = 'dev_toolboard' ORDER BY table_name"));

echo "\n--- current user info ---\n";
try {
	print_r(DB::select("SELECT USER() AS user"));
} catch (\Exception $e) {
	echo "Error running USER(): " . $e->getMessage() . "\n";
}

try {
	print_r(DB::select("SELECT CURRENT_USER() AS current_user"));
} catch (\Exception $e) {
	echo "Error running CURRENT_USER(): " . $e->getMessage() . "\n";
}

echo "\n--- grants for current_user ---\n";
try {
	print_r(DB::select("SHOW GRANTS FOR CURRENT_USER()"));
} catch (\Exception $e) {
	echo "Error running SHOW GRANTS: " . $e->getMessage() . "\n";
}

echo "\n--- row counts (quick health check) ---\n";
try {
	$u = DB::select("SELECT COUNT(*) AS cnt FROM users");
	$c = DB::select("SELECT COUNT(*) AS cnt FROM cache");
	$f = DB::select("SELECT COUNT(*) AS cnt FROM failed_jobs");
	echo "users: " . ($u[0]->cnt ?? 'N/A') . "\n";
	echo "cache: " . ($c[0]->cnt ?? 'N/A') . "\n";
	echo "failed_jobs: " . ($f[0]->cnt ?? 'N/A') . "\n";
} catch (\Exception $e) {
	echo "Error running counts: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
