<?php
session_start();

// 
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // validasi sederhana form login
    if ($username === 'Dina' && $password === '1708') {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
} elseif (isset($_POST['batal'])) {
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e6e6fa 0%, #d8bfd8 50%, #e6e6fa 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.98);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(147, 112, 219, 0.2);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 3.5rem;
            color: #9370db;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo h2 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .store-tag {
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(147, 112, 219, 0.3);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9370db;
            font-size: 1.1rem;
        }

        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e6e6fa;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
            color: #333;
        }

        .input-with-icon input:focus {
            outline: none;
            border-color: #9370db;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(147, 112, 219, 0.1);
        }

        .input-with-icon input::placeholder {
            color: #999;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .btn-login {
            flex: 2;
            padding: 14px;
            background: linear-gradient(135deg, #9370db, #d8bfd8);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(147, 112, 219, 0.4);
        }

        .btn-batal {
            flex: 1;
            padding: 14px;
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(147, 112, 219, 0.6);
            background: linear-gradient(135deg, #8367c7, #c8a8d8);
        }

        .btn-batal:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.6);
            background: linear-gradient(135deg, #5a6268, #9fa6b2);
        }

        .btn-login:active, .btn-batal:active {
            transform: translateY(0);
        }

        .error {
            background: linear-gradient(135deg, #ffb6c1, #ff69b4);
            color: white;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(255, 182, 193, 0.4);
        }

        .error i {
            margin-right: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 0.85rem;
            border-top: 1px solid #e6e6fa;
            padding-top: 20px;
        }

        .feature-text {
            text-align: center;
            margin-bottom: 25px;
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
            background: #f8f8ff;
            padding: 12px;
            border-radius: 10px;
            border-left: 4px solid #9370db;
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .login-container {
            animation: fadeIn 0.6s ease-out;
        }

        .logo-icon {
            animation: float 3s ease-in-out infinite;
        }

      
      </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-store"></i>
            </div>
            <h2>POLGAN MART</h2>
            <div class="store-tag">
                <i class="fas fa-tag"></i> Koperasi Terpercaya
            </div>
        </div>

       

        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username"><i class="fas fa-user-tie"></i> Username</label>
                <div class="input-with-icon">
                    <i class="fas fa-user-cog"></i>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'Dina'; ?>" required 
                           placeholder="Masukkan username ">
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-shield-alt"></i> Password</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required 
                           placeholder="Masukkan password ">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <button type="submit" name="batal" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>

       

        <div class="footer">
            <i class="fas fa-copyright"></i> 2025 POLGAN MART - 
        </div>
    </div>
</body>
</html>