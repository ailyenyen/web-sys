<?php
// classes/Resume.php
class Resume {
    private $conn;
    private $user_id;
    
    public function __construct($db, $user_id = 1) {
        $this->conn = $db;
        // IMPORTANT: All users edit the same resume (user_id = 1)
        // This is for the shared resume system
        $this->user_id = 1;
    }
    
    // Get user profile
    public function getProfile() {
        $query = "SELECT up.*, u.username, u.email, u.full_name 
                  FROM users u
                  LEFT JOIN user_profiles up ON u.id = up.user_id
                  WHERE u.id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update or create profile
    public function updateProfile($data) {
        // Check if profile exists
        $check = "SELECT id FROM user_profiles WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($check);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing profile
            $query = "UPDATE user_profiles SET 
                      title = :title,
                      phone = :phone,
                      location = :location,
                      linkedin = :linkedin,
                      github = :github,
                      summary = :summary,
                      languages = :languages,
                      updated_at = CURRENT_TIMESTAMP
                      WHERE user_id = :user_id";
        } else {
            // Insert new profile
            $query = "INSERT INTO user_profiles 
                      (user_id, title, phone, location, linkedin, github, summary, languages)
                      VALUES (:user_id, :title, :phone, :location, :linkedin, :github, :summary, :languages)";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':linkedin', $data['linkedin']);
        $stmt->bindParam(':github', $data['github']);
        $stmt->bindParam(':summary', $data['summary']);
        $stmt->bindParam(':languages', $data['languages']);
        
        return $stmt->execute();
    }
    
    // Get all education entries
    public function getEducation() {
        $query = "SELECT * FROM education 
                  WHERE user_id = :user_id 
                  ORDER BY display_order, id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Add education entry
    public function addEducation($data) {
        $query = "INSERT INTO education 
                  (user_id, degree, school, start_date, end_date, gpa, display_order)
                  VALUES (:user_id, :degree, :school, :start_date, :end_date, :gpa, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':degree', $data['degree']);
        $stmt->bindParam(':school', $data['school']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':gpa', $data['gpa']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Update education entry
    public function updateEducation($id, $data) {
        $query = "UPDATE education SET 
                  degree = :degree,
                  school = :school,
                  start_date = :start_date,
                  end_date = :end_date,
                  gpa = :gpa,
                  display_order = :display_order
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':degree', $data['degree']);
        $stmt->bindParam(':school', $data['school']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':gpa', $data['gpa']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Delete education entry
    public function deleteEducation($id) {
        $query = "DELETE FROM education WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    
    // Get all projects
    public function getProjects() {
        $query = "SELECT * FROM projects 
                  WHERE user_id = :user_id 
                  ORDER BY display_order, id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Add project
    public function addProject($data) {
        $query = "INSERT INTO projects 
                  (user_id, title, period, description, display_order)
                  VALUES (:user_id, :title, :period, :description, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':period', $data['period']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Update project
    public function updateProject($id, $data) {
        $query = "UPDATE projects SET 
                  title = :title,
                  period = :period,
                  description = :description,
                  display_order = :display_order
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':period', $data['period']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Delete project
    public function deleteProject($id) {
        $query = "DELETE FROM projects WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    
    // Get all skills grouped by category
    public function getSkills() {
        $query = "SELECT * FROM skills 
                  WHERE user_id = :user_id 
                  ORDER BY category, display_order, skill_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group by category
        $grouped = [];
        foreach ($skills as $skill) {
            $grouped[$skill['category']][] = $skill;
        }
        
        return $grouped;
    }
    
    // Add skill
    public function addSkill($data) {
        $query = "INSERT INTO skills 
                  (user_id, category, skill_name, display_order)
                  VALUES (:user_id, :category, :skill_name, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':skill_name', $data['skill_name']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Delete skill
    public function deleteSkill($id) {
        $query = "DELETE FROM skills WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    
    // Get all awards
    public function getAwards() {
        $query = "SELECT * FROM awards 
                  WHERE user_id = :user_id 
                  ORDER BY display_order, id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Add award
    public function addAward($data) {
        $query = "INSERT INTO awards 
                  (user_id, award_name, display_order)
                  VALUES (:user_id, :award_name, :display_order)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':award_name', $data['award_name']);
        $stmt->bindParam(':display_order', $data['display_order']);
        
        return $stmt->execute();
    }
    
    // Delete award
    public function deleteAward($id) {
        $query = "DELETE FROM awards WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
    
    // Get complete resume data
    public function getCompleteResume() {
        return [
            'profile' => $this->getProfile(),
            'education' => $this->getEducation(),
            'projects' => $this->getProjects(),
            'skills' => $this->getSkills(),
            'awards' => $this->getAwards()
        ];
    }
}
?>