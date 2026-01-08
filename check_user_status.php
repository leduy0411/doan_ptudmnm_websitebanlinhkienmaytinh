<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = getDB();
    echo "Database connection successful.\n";

    // Check for employee user
    $email = 'employee@techshop.com';
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "User found: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Status: " . $user['status'] . "\n";

        // Verify password 'password'
        if (password_verify('password', $user['password'])) {
            echo "Password 'password' is CORRECT.\n";
        } else {
            echo "Password 'password' is INCORRECT.\n";
            echo "Hash in DB: " . $user['password'] . "\n";
            echo "New hash for 'password': " . password_hash('password', PASSWORD_DEFAULT) . "\n";
        }
    } else {
        echo "User '$email' NOT FOUND in database.\n";

        // List all users
        echo "\nListing all users:\n";
        $stmt = $db->query("SELECT id, name, email, role, status FROM users");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- ID: {$row['id']}, Email: {$row['email']}, Role: {$row['role']}, Status: {$row['status']}\n";
        }
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
