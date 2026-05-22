<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['superadmin_logged_in'])) {
    header('Location: index.php'); exit;
}

require_once __DIR__ . '/../../include/db_master.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $master = stock_master_connect();
    if ($master) {
        $safe = mysqli_real_escape_string($master, $username);
        $res  = mysqli_query($master, "SELECT * FROM superadmins WHERE username='$safe' LIMIT 1");
        $row  = $res ? mysqli_fetch_assoc($res) : null;
        if ($row && $row['password'] === $password) {
            $_SESSION['superadmin_logged_in'] = true;
            $_SESSION['superadmin_username']  = $username;
            mysqli_close($master);
            header('Location: index.php'); exit;
        } else {
            $error = 'Invalid super admin credentials';
        }
        mysqli_close($master);
    } else {
        $error = 'Cannot connect to master database. Run setup first.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Super Admin — Stock Hub</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #060a14; overflow: hidden; }

.bg { position: fixed; inset: 0; background: radial-gradient(ellipse at 20% 50%, rgba(88,28,220,0.2) 0%, transparent 60%), radial-gradient(ellipse at 80% 20%, rgba(236,72,153,0.12) 0%, transparent 50%), #060a14; }
.grid-lines { position: fixed; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 50px 50px; }
.orb { position: absolute; border-radius: 50%; filter: blur(100px); }
.orb-1 { width: 600px; height: 600px; background: rgba(88,28,220,0.2); top: -200px; left: -200px; }
.orb-2 { width: 400px; height: 400px; background: rgba(236,72,153,0.12); bottom: -100px; right: -100px; }

.card {
    position: relative; z-index: 10;
    width: 100%; max-width: 420px;
    background: rgba(255,255,255,0.03);
    backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    padding: 48px 44px;
    box-shadow: 0 40px 100px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.06);
    margin: 20px;
}

.badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(88,28,220,0.2); border: 1px solid rgba(88,28,220,0.4);
    border-radius: 100px; padding: 6px 14px;
    font-size: 0.72rem; font-weight: 700; color: #a78bfa;
    letter-spacing: 0.1em; text-transform: uppercase;
    margin-bottom: 20px;
}

h1 { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-bottom: 6px; }
.sub { color: rgba(255,255,255,0.4); font-size: 0.88rem; margin-bottom: 32px; }

.field { margin-bottom: 18px; }
.field label { display: block; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); margin-bottom: 8px; }
.field-wrap { position: relative; }
.field-wrap i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.2); font-size: 0.85rem; pointer-events: none; }
.field-wrap input {
    width: 100%; background: rgba(255,255,255,0.05); border: 1.5px solid rgba(255,255,255,0.08); border-radius: 12px;
    color: #f1f5f9; font-size: 0.9rem; padding: 12px 16px 12px 44px; outline: none; transition: all 0.2s; font-family: 'Inter', sans-serif;
}
.field-wrap input::placeholder { color: rgba(255,255,255,0.18); }
.field-wrap input:focus { background: rgba(255,255,255,0.08); border-color: rgba(88,28,220,0.6); box-shadow: 0 0 0 3px rgba(88,28,220,0.15); }

.error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 11px 14px; color: #fca5a5; font-size: 0.83rem; display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }

.btn { width: 100%; padding: 13px; background: linear-gradient(135deg, #5b21b6, #7c3aed, #a855f7); color: #fff; border: none; border-radius: 12px; font-size: 0.92rem; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.2s; box-shadow: 0 8px 28px rgba(88,28,220,0.45); margin-top: 4px; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(88,28,220,0.6); }
.btn:active { transform: translateY(0); }

.back { text-align: center; margin-top: 20px; font-size: 0.8rem; color: rgba(255,255,255,0.25); }
.back a { color: rgba(167,139,250,0.6); text-decoration: none; }
.back a:hover { color: #a78bfa; }
</style>
</head>
<body>
<div class="bg">
    <div class="grid-lines"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<div class="card">
    <div class="badge"><i class="fas fa-shield-halved"></i> Super Admin</div>
    <h1>Platform<br>Control</h1>
    <p class="sub">Sign in to manage all shops and tenants.</p>

    <?php if ($error) : ?>
    <div class="error"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="field">
            <label>Username</label>
            <div class="field-wrap">
                <i class="fas fa-user-shield"></i>
                <input name="username" type="text" placeholder="superadmin" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
            </div>
        </div>
        <div class="field">
            <label>Password</label>
            <div class="field-wrap">
                <i class="fas fa-lock"></i>
                <input name="password" type="password" placeholder="••••••••" required autocomplete="current-password">
            </div>
        </div>
        <button type="submit" class="btn"><i class="fas fa-arrow-right-to-bracket" style="margin-right:8px"></i>Sign In</button>
    </form>

    <div class="back"><a href="../../login.php">← Back to Shop Login</a></div>
</div>
</body>
</html>
