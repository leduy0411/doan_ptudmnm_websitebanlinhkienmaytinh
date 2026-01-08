<?php
/**
 * Reset Employee Account - Run this once to fix login issues
 */
require_once __DIR__ . '/config/database.php';

try {
    $db = getDB();

    // Hash for "password"
    $passwordHash = password_hash('password', PASSWORD_DEFAULT);

    // Check if employee exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['employee@techshop.com']);
    $user = $stmt->fetch();

    if ($user) {
        // Update password
        $stmt = $db->prepare("UPDATE users SET password = ?, status = 'active' WHERE email = ?");
        $stmt->execute([$passwordHash, 'employee@techshop.com']);
        echo "<h2 style='color: green;'>✓ Đã reset mật khẩu tài khoản nhân viên!</h2>";
    } else {
        // Create new employee
        $stmt = $db->prepare("INSERT INTO users (name, email, password, phone, role, status, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'Nhân viên TechShop',
            'employee@techshop.com',
            $passwordHash,
            '0901234568',
            'employee',
            'active',
            1
        ]);
        echo "<h2 style='color: green;'>✓ Đã tạo tài khoản nhân viên mới!</h2>";
    }

    echo "<p><strong>Email:</strong> employee@techshop.com</p>";
    echo "<p><strong>Mật khẩu:</strong> password</p>";
    echo "<p><a href='login'>→ Đăng nhập ngay</a></p>";

    // Also fix admin account
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['admin@techshop.com']);
    $admin = $stmt->fetch();

    if ($admin) {
        $stmt = $db->prepare("UPDATE users SET password = ?, status = 'active' WHERE email = ?");
        $stmt->execute([$passwordHash, 'admin@techshop.com']);
        echo "<hr><p>Đã reset mật khẩu admin@techshop.com</p>";
    }

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Lỗi: " . $e->getMessage() . "</h2>";
}
?>