<?php
session_start();
include_once 'include/db.php';

$_SESSION['error'] = null;
$_SESSION['error_password'] = null;
$_SESSION['error_username'] = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (strlen($username) == 0) {
            $_SESSION['error_username'] =  "Please enter a username";
            $_POST['username'] = null;
        } else if (strlen($password) == 0) {
            $_SESSION['error_password'] =  "Please enter a password";
            $_POST['password'] = null;
        } else {
            $sql = "SELECT * FROM user WHERE user_name = '$username'";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $user_name = $row['user_name'];
                    $password_db = $row['password'];
                    $user_id = $row['user_id'];

                    if ($username == $user_name && $password == $password_db) {
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user'] = true;
                        $redirect = $_GET['redirect'] ?? 'index.php';
                        header("Location: $redirect");
                        die();
                    } else {
                        $_SESSION['error'] = "Invalid credentials";
                    }
                } else {
                    $_SESSION['error'] = "User not found";
                }
            } else {
                $_SESSION['error'] = "Database query failed";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $title = "Login"; $redirect_link = ""; $side_link = ""; include 'partials/title-meta.php'; ?>
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0b1120;
            overflow: hidden;
        }

        /* ── Animated Background ──────────────────────── */
        .login-bg {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0b1120 0%, #1a0e40 50%, #0b1120 100%);
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(124,58,237,0.35) 0%, transparent 70%);
            top: -150px; left: -150px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(236,72,153,0.25) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation-delay: -3s;
        }

        .orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(59,130,246,0.2) 0%, transparent 70%);
            top: 40%; left: 40%;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -40px) scale(1.05); }
            66%       { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ── Left Panel ───────────────────────────────── */
        .login-left {
            position: relative;
            z-index: 1;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            text-align: center;
        }

        .brand-logo {
            width: 100px;
            height: 100px;
            border-radius: 28px;
            object-fit: cover;
            box-shadow: 0 20px 60px rgba(124,58,237,0.4), 0 0 0 1px rgba(255,255,255,0.1);
            margin-bottom: 28px;
            animation: logoFloat 6s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        .brand-name {
            font-size: 2.8rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 14px;
        }

        .brand-name span {
            background: linear-gradient(135deg, #a78bfa, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            font-size: 1rem;
            color: rgba(255,255,255,0.45);
            font-weight: 400;
            max-width: 320px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            align-items: flex-start;
            max-width: 300px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.6);
            font-size: 0.875rem;
        }

        .feature-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .fi-purple { background: rgba(124,58,237,0.25); color: #a78bfa; }
        .fi-pink   { background: rgba(236,72,153,0.2);  color: #f472b6; }
        .fi-blue   { background: rgba(59,130,246,0.2);  color: #60a5fa; }
        .fi-teal   { background: rgba(20,184,166,0.2);  color: #2dd4bf; }

        /* ── Right Panel (Form) ───────────────────────── */
        .login-right {
            position: relative;
            z-index: 1;
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 28px;
            padding: 44px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .login-header { margin-bottom: 36px; }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-header p {
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
        }

        /* Input groups */
        .input-group { margin-bottom: 22px; }

        .input-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap input {
            width: 100%;
            background: rgba(255,255,255,0.06) !important;
            border: 1.5px solid rgba(255,255,255,0.1) !important;
            border-radius: 12px !important;
            color: #f1f5f9 !important;
            font-size: 0.9rem !important;
            font-weight: 400 !important;
            padding: 13px 16px 13px 46px !important;
            outline: none !important;
            transition: all 0.2s ease !important;
            font-family: 'Inter', sans-serif !important;
        }

        .input-wrap input::placeholder { color: rgba(255,255,255,0.2) !important; }

        .input-wrap input:focus {
            background: rgba(255,255,255,0.09) !important;
            border-color: rgba(124,58,237,0.7) !important;
            box-shadow: 0 0 0 4px rgba(124,58,237,0.15) !important;
        }

        .input-wrap input:focus + i,
        .input-wrap:focus-within i { color: #a78bfa !important; }

        .input-error {
            font-size: 0.75rem;
            color: #f87171;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Alert */
        .alert-error {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            color: #fca5a5;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 6px 24px rgba(124,58,237,0.4);
            position: relative;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            margin-top: 8px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(124,58,237,0.55);
        }

        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(0); }

        /* Footer note */
        .login-note {
            text-align: center;
            margin-top: 24px;
            color: rgba(255,255,255,0.28);
            font-size: 0.8rem;
        }

        .login-note strong { color: rgba(255,255,255,0.45); }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: rgba(255,255,255,0.15);
            font-size: 0.75rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        /* ── Responsive ───────────────────────────────── */
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; padding: 20px; }
            .login-card { padding: 32px 28px; }
        }
    </style>
</head>
<body>

<div class="login-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<!-- Left Branding Panel -->
<div class="login-left">
    <img src="assets/images/zuqemens.JPG" alt="Zuqemens Logo" class="brand-logo">
    <h1 class="brand-name">Zuqemens<br><span>Stock Hub</span></h1>
    <p class="brand-tagline">The complete inventory management system for your fashion brand.</p>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon fi-purple"><i class="fas fa-box-open"></i></div>
            <span>Real-time stock tracking across all product lines</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon fi-pink"><i class="fas fa-chart-line"></i></div>
            <span>Advanced sales analytics &amp; profit reports</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon fi-blue"><i class="fas fa-users"></i></div>
            <span>Multi-user role-based access control</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon fi-teal"><i class="fas fa-truck"></i></div>
            <span>Delivery, exchange &amp; refund management</span>
        </div>
    </div>
</div>

<!-- Right Form Panel -->
<div class="login-right">
    <div class="login-card">
        <div class="login-header">
            <h2>Welcome back</h2>
            <p>Sign in to your account to continue</p>
        </div>

        <?php if (!empty($_SESSION['error'])) : ?>
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="input-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Enter your username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required
                        autocomplete="username"
                    >
                </div>
                <?php if (!empty($_SESSION['error_username'])) : ?>
                <div class="input-error"><i class="fas fa-exclamation-circle"></i><?php echo htmlspecialchars($_SESSION['error_username']); ?></div>
                <?php endif; ?>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
                <?php if (!empty($_SESSION['error_password'])) : ?>
                <div class="input-error"><i class="fas fa-exclamation-circle"></i><?php echo htmlspecialchars($_SESSION['error_password']); ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-arrow-right-to-bracket" style="margin-right:8px;"></i> Sign In
            </button>
        </form>

        <div class="login-note">
            Don't have an account? <strong>Contact your administrator</strong>
        </div>
    </div>
</div>

</body>
</html>
