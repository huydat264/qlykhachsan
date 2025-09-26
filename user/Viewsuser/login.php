<?php
session_start(); // 👈 thêm dòng này ở ngay đầu

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - The Great Mission Hotel</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: url("https://radissonhotels.iceportal.com/image/radisson-hotel-danang/pool--outdoor/16256-128272-f78121812_3xl.jpg") no-repeat center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(8px);
      padding: 40px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      animation: fadeIn 1.2s ease;
    }

    .login-box h2 {
      text-align: center;
      color: #002060;
      margin-bottom: 25px;
    }

    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      background: #002060;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .login-box button:hover {
      background: #001040;
      transform: scale(1.03);
    }

    .login-box p {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-box a {
      color: #002060;
      text-decoration: none;
      font-weight: bold;
    }

    .login-box a:hover {
      text-decoration: underline;
    }

    .back-btn {
      display: block;
      text-align: center;
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #002060;
      border-radius: 6px;
      background: transparent;
      color: #002060;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      background: #002060;
      color: #fff;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .error {
      color: red;
      text-align: center;
      font-size: 14px;
    }
    
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
     <?php
if (!empty($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}
?>

<form method="post" action="../Controlleruser/DangKiDangNhapController.php?hanhdong=dangnhap">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <a href="trangchu.php" class="back-btn">← Back to Home</a>
  </div>
</body>
</html>
