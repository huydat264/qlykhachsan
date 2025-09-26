<?php
// register.php
include_once("../includes/db.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - The Great Mission Hotel</title>
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
    .register-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 40px;
      border-radius: 12px;
      width: 350px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      animation: fadeIn 1.2s ease;
    }
    .register-box h2 {
      text-align: center;
      color: #002060;
      margin-bottom: 25px;
    }
    .register-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .register-box button {
      width: 100%;
      padding: 12px;
      background: #002060;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .register-box button:hover {
      background: #001040;
    }
    .register-box p {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .register-box a {
      color: #002060;
      text-decoration: none;
      font-weight: bold;
    }
    .register-box a:hover {
      text-decoration: underline;
    }
    .message {
      text-align: center;
      font-size: 14px;
      margin-bottom: 10px;
      color: red;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Create Account</h2>
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
   <form method="post" action="../Controlleruser/DangKiDangNhapController.php?hanhdong=dangki">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
    <a href="trangchu.php" class="back-btn">← Back to Home</a>
  </div>
</body>
</html>