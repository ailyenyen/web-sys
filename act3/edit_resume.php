<?php
require_once 'config/database.php';
require_once 'classes/Resume.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// IMPORTANT: All users edit the SAME resume (user_id = 1)
$user_id = 1; // Shared resume for everyone
$resume = new Resume($pdo, $user_id);

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_profile':
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'linkedin' => $_POST['linkedin'] ?? '',
                    'github' => $_POST['github'] ?? '',
                    'summary' => $_POST['summary'] ?? '',
                    'languages' => $_POST['languages'] ?? ''
                ];
                
                if ($resume->updateProfile($data)) {
                    $message = 'Profile updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Failed to update profile.';
                    $message_type = 'error';
                }
                break;
                
            case 'add_education':
                $data = [
                    'degree' => $_POST['degree'] ?? '',
                    'school' => $_POST['school'] ?? '',
                    'start_date' => $_POST['start_date'] ?? '',
                    'end_date' => $_POST['end_date'] ?? '',
                    'gpa' => $_POST['gpa'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->addEducation($data)) {
                    $message = 'Education added successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'delete_education':
                if ($resume->deleteEducation($_POST['id'])) {
                    $message = 'Education deleted successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'add_project':
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'period' => $_POST['period'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->addProject($data)) {
                    $message = 'Project added successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'delete_project':
                if ($resume->deleteProject($_POST['id'])) {
                    $message = 'Project deleted successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'add_skill':
                $data = [
                    'category' => $_POST['category'] ?? '',
                    'skill_name' => $_POST['skill_name'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->addSkill($data)) {
                    $message = 'Skill added successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'delete_skill':
                if ($resume->deleteSkill($_POST['id'])) {
                    $message = 'Skill deleted successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'add_award':
                $data = [
                    'award_name' => $_POST['award_name'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->addAward($data)) {
                    $message = 'Award added successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'delete_award':
                if ($resume->deleteAward($_POST['id'])) {
                    $message = 'Award deleted successfully!';
                    $message_type = 'success';
                }
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Get resume data
$resume_data = $resume->getCompleteResume();
$profile = $resume_data['profile'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
        }

        .top-nav {
            background-color: #495057;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .top-nav h1 {
            font-size: 1.3em;
            font-weight: 500;
        }

        .top-nav-links {
            display: flex;
            gap: 15px;
        }

        .top-nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .top-nav-links a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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

        .section-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        .section-card h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.4em;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 0.95em;
            font-family: inherit;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #495057;
            box-shadow: 0 0 0 2px rgba(73, 80, 87, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.95em;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #495057;
            color: white;
        }

        .btn-primary:hover {
            background-color: #343a40;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85em;
        }

        .item-list {
            margin-top: 20px;
        }

        .list-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 12px;
            border-left: 3px solid #6c757d;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .list-item-content {
            flex: 1;
        }

        .list-item-content h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .list-item-content p {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 3px;
        }

        .list-item-actions {
            display: flex;
            gap: 8px;
        }

        .collapsible {
            background-color: #e9ecef;
            color: #2c3e50;
            cursor: pointer;
            padding: 12px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 1em;
            font-weight: 500;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .collapsible:hover {
            background-color: #dee2e6;
        }

        .collapsible:after {
            content: '\002B';
            color: #495057;
            font-weight: bold;
            float: right;
        }

        .collapsible.active:after {
            content: "\2212";
        }

        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .collapsible-content.active {
            padding: 20px;
            border: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .top-nav {
                flex-direction: column;
                gap: 15px;
            }
            
            .list-item {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <h1>📝 Resume Dashboard</h1>
        <div class="top-nav-links">
            <a href="public_resume.php?id=<?php echo $user_id; ?>" target="_blank">👁️ View Public Resume</a>
            <a href="resume.php">🏠 My Resume</a>
            <a href="?logout=1">🚪 Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Personal Information -->
        <div class="section-card">
            <h2>Personal Information</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>" readonly style="background-color: #e9ecef;">
                        <small style="color: #6c757d;">Change in account settings</small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" readonly style="background-color: #e9ecef;">
                        <small style="color: #6c757d;">Change in account settings</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Professional Title *</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($profile['title'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>" placeholder="City, State/Province, Country">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="linkedin">LinkedIn Profile</label>
                        <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($profile['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="form-group">
                        <label for="github">GitHub Profile</label>
                        <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($profile['github'] ?? ''); ?>" placeholder="https://github.com/username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="summary">Professional Summary *</label>
                    <textarea id="summary" name="summary" required><?php echo htmlspecialchars($profile['summary'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="languages">Languages (comma-separated)</label>
                    <input type="text" id="languages" name="languages" value="<?php echo htmlspecialchars($profile['languages'] ?? ''); ?>" placeholder="English, Filipino, Spanish">
                </div>

                <button type="submit" class="btn btn-primary">💾 Save Profile</button>
            </form>
        </div>

        <!-- Education Section -->
        <div class="section-card">
            <h2>Education</h2>
            
            <button type="button" class="collapsible">➕ Add New Education</button>
            <div class="collapsible-content">
                <form method="POST">
                    <input type="hidden" name="action" value="add_education">
                    
                    <div class="form-group">
                        <label for="degree">Degree *</label>
                        <input type="text" id="degree" name="degree" required placeholder="e.g., Bachelor of Science in Computer Science">
                    </div>
                    
                    <div class="form-group">
                        <label for="school">School/University *</label>
                        <input type="text" id="school" name="school" required>
                    </div>
                    
                    <div class="form-row">