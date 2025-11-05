
<?php

require_once 'config/database.php';
require_once 'classes/User.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Validation
    if (empty($username) || empty($password)) {
        $message = 'All fields are required!';
        $message_type = 'error';
    } else {
        // Simple login check for assignment requirements
        if ($username === 'admin' && $password === '1234') {
            $message = 'Login Successful';
            $message_type = 'success';
            
            // Set session
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['logged_in'] = true;
            
            // Redirect to resume after 2 seconds
            header("Location: resume.php");
            exit();

        } else {
            $message = 'Invalid Username or Password';
            $message_type = 'error';
        }
        
        
        // For database authentication (uncomment when ready to use database)
        require_once 'config/database.php'; // this defines $pdo
        $db = $pdo;

        $user = new User($db);
        
        $user_data = $user->login($username, $password);
        
        if ($user_data) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['logged_in'] = true;
            
            $message = 'Login Successful';
            $message_type = 'success';
            
            header("Refresh: 2; url=resume.php");
        } else {
            $message = 'Invalid Username or Password';
            $message_type = 'error';
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jan Mayen Mallen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            border: 1px solid #e9ecef;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 0.95em;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 0.95em;
            transition: border-color 0.3s ease;
            background-color: #fff;
        }

        .form-group input:focus {
            outline: none;
            border-color: #495057;
            box-shadow: 0 0 0 2px rgba(73, 80, 87, 0.1);
        }

        .login-button {
            width: 100%;
            background-color: #495057;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .login-button:hover {
            background-color: #343a40;
        }

        .login-button:active {
            transform: translateY(1px);
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .auth-links {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .auth-links a {
            color: #495057;
            text-decoration: none;
            font-size: 0.9em;
            margin: 0 10px;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .demo-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 3px solid #6c757d;
        }

        .demo-info h4 {
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .demo-info p {
            color: #6c757d;
            font-size: 0.85em;
            margin-bottom: 5px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome Back</h1>
            <p>Sign in to view the resume</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>


        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="login-button">Login</button>
        </form>

        <div class="auth-links">
            <a href="signup.php">Don't have an account? Sign up</a>
            <span>|</span>
            <a href="resume.php">View Resume (Public)</a>
        </div>
    </div>
</body>
</html>