<?php
require_once __DIR__ . '/app/core/Env.php';
require_once __DIR__ . '/app/core/Database.php';

Env::load(__DIR__ . '/.env');

try {
    echo "Testing Database Connection...\n";
    $db = Database::connect();
    echo "Database Connected Successfully!\n";
} catch (Exception $e) {
    echo "Database Connection Failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if any users exist
$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nTotal Users Found: " . count($users) . "\n";
foreach ($users as $user) {
    echo "- ID: {$user['id']}, Username: {$user['username']}, Role: {$user['role']}\n";
    echo "  Password Hash: {$user['password']}\n";
}

// Test specific login if you want (e.g. admin/password)
$testUsername = 'admin';
$testPassword = 'password';

echo "\nTesting login for user: $testUsername / $testPassword\n";
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $testUsername]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "User found!\n";
    if (password_verify($testPassword, $user['password'])) {
        echo "Password Match! Login Valid.\n";
    } else {
        echo "Password Mismatch!\n";
        echo "Hash: " . $user['password'] . "\n";
        echo "Verify result: " . (password_verify($testPassword, $user['password']) ? 'true' : 'false') . "\n";
    }
} else {
    echo "User not found.\n";
}
