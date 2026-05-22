<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'mbchamasah@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "Password reset successfully to 'password123' for {$user->email}\n";
} else {
    echo "User not found\n";
}
