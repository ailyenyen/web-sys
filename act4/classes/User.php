<?php
// classes/User.php
require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'users';
    
    public $id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Register new user
    public function register() {
        $query = "INSERT INTO " . $this->table . " 
                 (username, password, email, full_name) 
                 VALUES (:username, :password, :email, :full_name)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':full_name', $this->full_name);
        
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            if ($e->getCode() == '23505') { // Unique constraint violation
                return 'username_exists';
            }
            return false;
        }
        
        return false;
    }
    
    // Login user
    public function login($username, $password) {
        $query = "SELECT id, username, password, email, full_name 
                 FROM " . $this->table . " 
                 WHERE username = :username AND is_active = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password'])) {
                // Update last login
                $this->updateLastLogin($row['id']);
                
                return [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                    'full_name' => $row['full_name']
                ];
            }
        }
        
        return false;
    }
    
    // Simple login for the assignment (username: admin, password: 1234)
    public function simpleLogin($username, $password) {
        if ($username === 'admin' && $password === '1234') {
            return [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'full_name' => 'Administrator'
            ];
        }
        return false;
    }
    
    // Check if username exists
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Update last login time
    private function updateLastLogin($user_id) {
        $query = "UPDATE " . $this->table . " 
                 SET last_login = CURRENT_TIMESTAMP 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
    }
}
?>