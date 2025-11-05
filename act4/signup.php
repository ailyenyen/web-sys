<?php
require_once 'config/database.php';
require_once 'classes/User.php';

$message = '';
$message_type = '';
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = [
        'username' => trim($_POST['username'] ?? ''),
        'password' => trim($_POST['password'] ?? ''),
        'confirm_password' => trim($_POST['confirm_password'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? '')
    ];
    
    // Validation
    $errors = [];
    
    if (empty($form_data['username'])) {
        $errors[] = 'Username is required';
    }
    
    if (empty($form_data['password'])) {
        $errors[] = 'Password is required';
    } elseif (strlen($form_data['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }
    
    if ($form_data['password'] !== $form_data['confirm_password']) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($form_data['email'])) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($form_data['full_name'])) {
        $errors[] = 'Full name is required';
    }
    
    if (!empty($errors)) {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    } else {
        // Try to register user
        require_once 'config/database.php'; // this defines $pdo
        $db = $pdo;

        
        if ($db) {
            $user = new User($db);
            $user->username = $form_data['username'];
            $user->password = $form_data['password'];
            $user->email = $form_data['email'];
            $user->full_name = $form_data['full_name'];
            
            $result = $user->register();
            
            if ($result === true) {
                $message = 'Account created successfully! You can now log in.';
                $message_type = 'success';
                $form_data = []; // Clear form
                header("Refresh: 3; url=login.php");
            } elseif ($result === 'username_exists') {
                $message = 'Username already exists. Please choose a different username.';
                $message_type = 'error';
            } else {
                $message = 'Registration failed. Please try again.';
                $message_type = 'error';
            }
        } else {
            $message = 'Database connection failed. Please try again later.';
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
    <title>Sign Up - Jan Mayen Mallen</title>
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

        .signup-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 450px;
            border: 1px solid #e9ecef;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .signup-header h1 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .signup-header p {
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

        .signup-button {
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

        .signup-button:hover {
            background-color: #343a40;
        }

        .signup-button:active {
            transform: translateY(1px);
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            line-height: 1.4;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 30px 20px;
            }
            
            .signup-header h1 {
                font-size: 1.5em;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .password-requirements {
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h1>Create Account</h1>
            <p>Sign up to access the resume system</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    value="<?php echo htmlspecialchars($form_data['full_name'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        autocomplete="new-password"
                        required
                    >
                    <div class="password-requirements">
                        At least 6 characters
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="signup-button">Create Account</button>
        </form>

        <div class="auth-links">
            <a href="login.php">Already have an account? Sign in</a>
            <span>|</span>
            <a href="resume.php">View Resume (Public)</a>
        </div>
    </div>

    <script>
        // Simple password confirmation validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        function validatePassword() {
            if (password.value != confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirmPassword.onkeyup = validatePassword;
    </script>
</body>
</html>