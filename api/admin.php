<?php
session_start();
$file = 'data.json';
$config = json_decode(file_get_contents($file), true);

// LOGIC LOGIN ADMIN
if (isset($_POST['login_admin'])) {
    if ($_POST['admin_pass'] == $config['password_admin']) {
        $_SESSION['admin_logged'] = true;
    } else {
        $error = "❌ Password Salah!";
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
}

// UPDATE CONFIG
if (isset($_POST['update_config']) && isset($_SESSION['admin_logged'])) {
    file_put_contents($file, json_encode($_POST, JSON_PRETTY_PRINT));
    header("Location: admin.php?success=1");
}

$is_logged = isset($_SESSION['admin_logged']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Setting</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding-top: 50px; }
        .box { background: #fff; width: 100%; max-width: 400px; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #0070ff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .logout { display: block; text-align: center; margin-top: 15px; color: red; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
    <div class="box">
        <?php if (!$is_logged): ?>
            <h2 style="margin-top:0;">🔐 Admin Panel</h2>
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST">
                <input type="password" name="admin_pass" placeholder="Masukkan Pass Admin" required>
                <button type="submit" name="login_admin">MASUK</button>
            </form>
        <?php else: ?>
            <h2 style="margin-top:0;">⚙️ Konfigurasi</h2>
            <?php if(isset($_GET['success'])) echo "<p style='color:green;'>✅ Berhasil Disimpan!</p>"; ?>
            <form method="POST">
                <label>Bot Token:</label>
                <input type="text" name="bot_token" value="<?php echo $config['bot_token']; ?>">
                <label>Chat ID:</label>
                <input type="text" name="chat_id" value="<?php echo $config['chat_id']; ?>">
                <label>Password Admin Baru:</label>
                <input type="text" name="password_admin" value="<?php echo $config['password_admin']; ?>">
                <hr>
                <label>Nama File:</label>
                <input type="text" name="nama" value="<?php echo $config['nama']; ?>">
                <label>Ukuran File:</label>
                <input type="text" name="ukuran" value="<?php echo $config['ukuran']; ?>">
                <button type="submit" name="update_config">SIMPAN PERUBAHAN</button>
            </form>
            <a href="?logout=1" class="logout">Keluar/Logout</a>
        <?php endif; ?>
    </div>
</body>
</html>
