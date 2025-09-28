<?php
include 'db.php';
// Bắt đầu session ở đầu file
session_start();

$error = '';

// Kiem tra neu nguoi dung da dang nhap (user_id là biến chuẩn)
if (isset($_SESSION['user_id'])) {
    // Nếu ĐÃ đăng nhập, chuyển hướng thẳng đến index.php và THOÁT
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lấy thông tin tài khoản và ROLE từ cơ sở dữ liệu
    $stmt = $conn->prepare("SELECT id_taikhoan, password, username, role FROM taikhoan WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // So sánh mật khẩu trực tiếp (Lưu ý về bảo mật)
            if ($password === $user['password']) {
                // Đăng nhập thành công: Lưu ID, Tên và ROLE vào SESSION
                $_SESSION['user_id'] = $user['id_taikhoan'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_role'] = $user['role']; // 'ADMIN' hoặc 'NHANVIEN'
                
                // THÊM: Biến này là biến bạn đang dùng để kiểm tra trong auth.php
                $_SESSION['logged_in'] = true; 
                
                // CHUYỂN HƯỚNG VỀ TRANG CHỦ (index.php) sau khi đăng nhập thành công
                header("Location: index.php"); 
                exit();
            } else {
                $error = "Mật khẩu không đúng.";
            }
        } else {
            $error = "Tên tài khoản không tồn tại.";
        }
        $stmt->close();
    } else {
        $error = "Lỗi truy vấn: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://img.lovepik.com/bg/20240514/Stunning-3D-Interior-Visualization-Hotel-Lobby-Hallway-Background_11969099_wh1200.jpg');
            background-size: cover;
            background-position: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        .login-container h2 {
            margin-bottom: 30px;
            color: #002060;
            font-size: 28px;
            font-weight: 600;
        }
        .login-container .error {
            color: #d9534f;
            margin-bottom: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }
        .login-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: #002060;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .login-button:hover {
            background-color: #003366;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập hệ thống</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Tên tài khoản</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
