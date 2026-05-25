<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
$admin = User::where('role', 'administrator')->first();
if ($admin) {
    echo "Admin Email: " . $admin->email . "\n";
    echo "Admin Name: " . $admin->name . "\n";
    echo "Admin Role: " . $admin->role . "\n";
} else {
    echo "No admin found\n";
}
