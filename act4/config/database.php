<?php
// db.php - Simple database connection
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'resume_system';
$username = 'postgres';  
$password = 'Alohamora7';  

// Create connection
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function login($user_data) {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Simple user authentication
function authenticate($username, $password) {
    global $pdo;
    
    // First check simple credentials for assignment
    if ($username === 'admin' && $password === '1234') {
        return [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@example.com'
        ];
    }
    
    // Then check database
    $stmt = $pdo->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return false;
}

// Register new user
function registerUser($username, $password, $email) {
    global $pdo;
    
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashed_password, $email]);
    } catch(PDOException $e) {
        return false;
    }
}
?>