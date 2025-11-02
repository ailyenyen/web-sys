<?php
require_once 'config/database.php';
require_once 'classes/Resume.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// IMPORTANT: All users edit the SAME resume (user_id = 1)
$user_id = 1;
$resume = new Resume($pdo, $user_id);

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_user_info':
                // Update user table (name and email)
                $update_query = "UPDATE users SET 
                                full_name = :full_name,
                                email = :email
                                WHERE id = :user_id";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':full_name', $_POST['full_name']);
                $stmt->bindParam(':email', $_POST['email']);
                $stmt->bindParam(':user_id', $user_id);
                
                if ($stmt->execute()) {
                    $message = 'User information updated successfully!';
                    $message_type = 'success';
                } else {
                    $message = 'Failed to update user information.';
                    $message_type = 'error';
                }
                break;
                
            case 'update_profile':
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'country' => $_POST['country'] ?? '',
                    'province' => $_POST['province'] ?? '',
                    'city' => $_POST['city'] ?? '',
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
                
            case 'update_education':
                $data = [
                    'degree' => $_POST['degree'] ?? '',
                    'school' => $_POST['school'] ?? '',
                    'start_date' => $_POST['start_date'] ?? '',
                    'end_date' => $_POST['end_date'] ?? '',
                    'gpa' => $_POST['gpa'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->updateEducation($_POST['id'], $data)) {
                    $message = 'Education updated successfully!';
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
                
            case 'update_project':
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'period' => $_POST['period'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'display_order' => $_POST['display_order'] ?? 0
                ];
                
                if ($resume->updateProject($_POST['id'], $data)) {
                    $message = 'Project updated successfully!';
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

// Get edit mode from URL
$edit_education_id = $_GET['edit_education'] ?? null;
$edit_project_id = $_GET['edit_project'] ?? null;

// Handle logout
if (isset($_GET['logout'])) {
    logout();
    header('Location: login.php');
    exit();
}

// Location data for dropdowns
$countries = [
    'US' => 'United States',
    'CA' => 'Canada',
    'PH' => 'Philippines',
    'UK' => 'United Kingdom',
    'AU' => 'Australia'
];

$provinces = [
    'US' => [
        'CA' => 'California',
        'TX' => 'Texas',
        'NY' => 'New York',
        'FL' => 'Florida',
        'IL' => 'Illinois'
    ],
    'CA' => [
        'ON' => 'Ontario',
        'QC' => 'Quebec',
        'BC' => 'British Columbia',
        'AB' => 'Alberta',
        'MB' => 'Manitoba'
    ],
    'PH' => [
        'NCR' => 'National Capital Region',
        'CAL' => 'Calabarzon',
        'CV' => 'Central Visayas',
        'NLV' => 'Northern Luzon',
        'MLV' => 'Metro Luzon'
    ],
    'UK' => [
        'ENG' => 'England',
        'SCT' => 'Scotland',
        'WLS' => 'Wales',
        'NIR' => 'Northern Ireland'
    ],
    'AU' => [
        'NSW' => 'New South Wales',
        'VIC' => 'Victoria',
        'QLD' => 'Queensland',
        'WA' => 'Western Australia',
        'SA' => 'South Australia'
    ]
];

$cities = [
    'CA' => [
        'LA' => 'Los Angeles',
        'SF' => 'San Francisco',
        'SD' => 'San Diego',
        'SJ' => 'San Jose'
    ],
    'TX' => [
        'HOU' => 'Houston',
        'DAL' => 'Dallas',
        'AUS' => 'Austin',
        'SAT' => 'San Antonio'
    ],
    'NY' => [
        'NYC' => 'New York City',
        'BUF' => 'Buffalo',
        'ROC' => 'Rochester',
        'SYR' => 'Syracuse'
    ],
    'ON' => [
        'TOR' => 'Toronto',
        'OTT' => 'Ottawa',
        'MSH' => 'Mississauga',
        'HAM' => 'Hamilton'
    ],
    'QC' => [
        'MON' => 'Montreal',
        'QUE' => 'Quebec City',
        'LAV' => 'Laval',
        'GAT' => 'Gatineau'
    ],
    'NCR' => [
        'MNL' => 'Manila',
        'QC' => 'Quezon City',
        'MKT' => 'Makati',
        'TAG' => 'Taguig'
    ],
    'CAL' => [
        'ANT' => 'Antipolo',
        'BAT' => 'Batangas City',
        'LUC' => 'Lucena',
        'SBL' => 'Santa Rosa'
    ],
    'ENG' => [
        'LON' => 'London',
        'MAN' => 'Manchester',
        'BIR' => 'Birmingham',
        'LEE' => 'Leeds'
    ],
    'SCT' => [
        'GLW' => 'Glasgow',
        'EDI' => 'Edinburgh',
        'ABD' => 'Aberdeen',
        'DUN' => 'Dundee'
    ],
    'NSW' => [
        'SYD' => 'Sydney',
        'NEW' => 'Newcastle',
        'WOL' => 'Wollongong',
        'CEN' => 'Central Coast'
    ],
    'VIC' => [
        'MEL' => 'Melbourne',
        'GEEL' => 'Geelong',
        'BAL' => 'Ballarat',
        'BEN' => 'Bendigo'
    ]
];

// Parse existing location data if available
$existing_country = '';
$existing_province = '';
$existing_city = '';

if (!empty($profile['location'])) {
    $location_parts = explode(',', $profile['location']);
    if (count($location_parts) >= 3) {
        $existing_city = trim($location_parts[0]);
        $existing_province = trim($location_parts[1]);
        $existing_country = trim($location_parts[2]);
    }
}
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

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
            font-size: 0.85em;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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
            text-decoration: none;
            display: inline-block;
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

        .btn-warning {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #ced4da;
        }

        .btn-warning:hover {
            background-color: #dee2e6;
        }

        .btn-danger {
            background-color: #e9ecef;
            color: #dc3545;
            border: 1px solid #ced4da;
        }

        .btn-danger:hover {
            background-color: #f8d7da;
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
        }

        .list-item-header {
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
            flex-shrink: 0;
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
            content: '+';
            color: #495057;
            font-weight: bold;
            float: right;
            font-size: 1.2em;
        }

        .collapsible.active:after {
            content: "×";
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
            max-height: 1000px;
            overflow-y: auto;
        }

        .edit-form {
            background-color: #fff9e6;
            border: 2px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .edit-form h4 {
            color: #856404;
            margin-bottom: 15px;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85em;
            margin-top: 5px;
            display: none;
        }

        @media (max-width: 768px) {
            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
            
            .top-nav {
                flex-direction: column;
                gap: 15px;
            }
            
            .list-item-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <h1>Resume Dashboard</h1>
        <div class="top-nav-links">
            <a href="resume.php">View Resume</a>
            <a href="?logout=1">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- User Information (Name & Email) -->
        <div class="section-card">
            <h2>User Information</h2>
            <form method="POST" id="user-info-form">
                <input type="hidden" name="action" value="update_user_info">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>" 
                               required minlength="2" maxlength="100">
                        <div class="error-message" id="full_name_error">Name must be at least 2 characters long</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" required>
                        <div class="error-message" id="email_error">Please enter a valid email address</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save User Info</button>
            </form>
        </div>

        <!-- Professional Information -->
        <div class="section-card">
            <h2>Professional Information</h2>
            <form method="POST" id="profile-form">
                <input type="hidden" name="action" value="update_profile">

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Professional Title *</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($profile['title'] ?? ''); ?>" 
                               required minlength="3" maxlength="100">
                        <div class="error-message" id="title_error">Title must be at least 3 characters long</div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>"
                               minlength="10" maxlength="20">
                        <div class="error-message" id="phone_error">Phone number must be at least 10 digits</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $code => $name): ?>
                                    <option value="<?php echo $code; ?>" <?php echo ($existing_country === $name) ? 'selected' : ''; ?>>
                                        <?php echo $name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="province">State/Province</label>
                            <select id="province" name="province">
                                <option value="">Select State/Province</option>
                                <?php 
                                if (!empty($existing_country)) {
                                    $country_code = array_search($existing_country, $countries);
                                    if ($country_code && isset($provinces[$country_code])) {
                                        foreach ($provinces[$country_code] as $code => $name) {
                                            $selected = ($existing_province === $name) ? 'selected' : '';
                                            echo "<option value='$code' $selected>$name</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <select id="city" name="city">
                                <option value="">Select City</option>
                                <?php 
                                if (!empty($existing_province)) {
                                    if (isset($cities[$existing_province])) {
                                        foreach ($cities[$existing_province] as $code => $name) {
                                            $selected = ($existing_city === $name) ? 'selected' : '';
                                            echo "<option value='$code' $selected>$name</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="linkedin">LinkedIn Profile</label>
                        <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($profile['linkedin'] ?? ''); ?>" 
                               placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="form-group">
                        <label for="github">GitHub Profile</label>
                        <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($profile['github'] ?? ''); ?>" 
                               placeholder="https://github.com/username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="summary">Professional Summary *</label>
                    <textarea id="summary" name="summary" required minlength="50" maxlength="1000"><?php echo htmlspecialchars($profile['summary'] ?? ''); ?></textarea>
                    <div class="error-message" id="summary_error">Summary must be at least 50 characters long</div>
                </div>

                <div class="form-group">
                    <label for="languages">Languages (comma-separated)</label>
                    <input type="text" id="languages" name="languages" value="<?php echo htmlspecialchars($profile['languages'] ?? ''); ?>" 
                           placeholder="English, Filipino, Spanish" minlength="2" maxlength="200">
                    <div class="error-message" id="languages_error">Please enter at least one language</div>
                </div>

                <button type="submit" class="btn btn-primary">Save Professional Info</button>
            </form>
        </div>

        <!-- Education Section -->
        <div class="section-card">
            <h2>Education</h2>
            
            <button type="button" class="collapsible">+ Add New Education</button>
            <div class="collapsible-content">
                <form method="POST" id="education-form">
                    <input type="hidden" name="action" value="add_education">
                    
                    <div class="form-group">
                        <label for="degree">Degree *</label>
                        <input type="text" id="degree" name="degree" required minlength="3" maxlength="200" 
                               placeholder="e.g., Bachelor of Science in Computer Science">
                        <div class="error-message" id="degree_error">Degree must be at least 3 characters long</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="school">School/University *</label>
                        <input type="text" id="school" name="school" required minlength="2" maxlength="200">
                        <div class="error-message" id="school_error">School name must be at least 2 characters long</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="text" id="start_date" name="start_date" placeholder="e.g., Aug 2023" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="text" id="end_date" name="end_date" placeholder="e.g., Present or May 2027" maxlength="20">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="gpa">GPA (optional)</label>
                        <input type="text" id="gpa" name="gpa" placeholder="e.g., 3.8/4.0" maxlength="20">
                    </div>
                    
                    <div class="form-group">
                        <label for="edu_display_order">Display Order</label>
                        <input type="number" id="edu_display_order" name="display_order" value="0" min="0">
                        <small>Lower numbers appear first</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Add Education</button>
                </form>
            </div>
            
            <div class="item-list">
                <?php foreach ($resume_data['education'] as $edu): ?>
                    <?php if ($edit_education_id == $edu['id']): ?>
                        <!-- Edit Form -->
                        <div class="edit-form">
                            <h4>Editing Education</h4>
                            <form method="POST">
                                <input type="hidden" name="action" value="update_education">
                                <input type="hidden" name="id" value="<?php echo $edu['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Degree *</label>
                                    <input type="text" name="degree" value="<?php echo htmlspecialchars($edu['degree']); ?>" required minlength="3" maxlength="200">
                                </div>
                                
                                <div class="form-group">
                                    <label>School/University *</label>
                                    <input type="text" name="school" value="<?php echo htmlspecialchars($edu['school']); ?>" required minlength="2" maxlength="200">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="text" name="start_date" value="<?php echo htmlspecialchars($edu['start_date']); ?>" maxlength="20">
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="text" name="end_date" value="<?php echo htmlspecialchars($edu['end_date']); ?>" maxlength="20">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>GPA</label>
                                    <input type="text" name="gpa" value="<?php echo htmlspecialchars($edu['gpa']); ?>" maxlength="20">
                                </div>
                                
                                <div class="form-group">
                                    <label>Display Order</label>
                                    <input type="number" name="display_order" value="<?php echo $edu['display_order']; ?>" min="0">
                                </div>
                                
                                <button type="submit" class="btn btn-success">Save Changes</button>
                                <a href="edit_resume.php" class="btn btn-warning">Cancel</a>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Display Item -->
                        <div class="list-item">
                            <div class="list-item-header">
                                <div class="list-item-content">
                                    <h4><?php echo htmlspecialchars($edu['degree']); ?></h4>
                                    <p><strong><?php echo htmlspecialchars($edu['school']); ?></strong></p>
                                    <p><?php echo htmlspecialchars($edu['start_date']); ?> - <?php echo htmlspecialchars($edu['end_date']); ?></p>
                                    <?php if ($edu['gpa']): ?>
                                        <p>GPA: <?php echo htmlspecialchars($edu['gpa']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="list-item-actions">
                                    <a href="?edit_education=<?php echo $edu['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_education">
                                        <input type="hidden" name="id" value="<?php echo $edu['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this education entry?')">×</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php if (empty($resume_data['education'])): ?>
                    <p style="color: #6c757d; text-align: center; padding: 20px;">No education entries yet. Add your first one above!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="section-card">
            <h2>Projects</h2>
            
            <button type="button" class="collapsible">+ Add New Project</button>
            <div class="collapsible-content">
                <form method="POST" id="project-form">
                    <input type="hidden" name="action" value="add_project">
                    
                    <div class="form-group">
                        <label for="project_title">Project Title *</label>
                        <input type="text" id="project_title" name="title" required minlength="3" maxlength="200">
                        <div class="error-message" id="project_title_error">Project title must be at least 3 characters long</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="period">Time Period</label>
                        <input type="text" id="period" name="period" placeholder="e.g., April 2025 - May 2025" maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" required minlength="10" maxlength="1000" 
                                  placeholder="Describe your project. Each line will be a bullet point."></textarea>
                        <div class="error-message" id="description_error">Description must be at least 10 characters long</div>
                        <small>Tip: Each line will be displayed as a separate bullet point</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="proj_display_order">Display Order</label>
                        <input type="number" id="proj_display_order" name="display_order" value="0" min="0">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Add Project</button>
                </form>
            </div>
            
            <div class="item-list">
                <?php foreach ($resume_data['projects'] as $project): ?>
                    <?php if ($edit_project_id == $project['id']): ?>
                        <!-- Edit Form -->
                        <div class="edit-form">
                            <h4>Editing Project</h4>
                            <form method="POST">
                                <input type="hidden" name="action" value="update_project">
                                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Project Title *</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required minlength="3" maxlength="200">
                                </div>
                                
                                <div class="form-group">
                                    <label>Time Period</label>
                                    <input type="text" name="period" value="<?php echo htmlspecialchars($project['period']); ?>" maxlength="50">
                                </div>
                                
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea name="description" required minlength="10" maxlength="1000"><?php echo htmlspecialchars($project['description']); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Display Order</label>
                                    <input type="number" name="display_order" value="<?php echo $project['display_order']; ?>" min="0">
                                </div>
                                
                                <button type="submit" class="btn btn-success">Save Changes</button>
                                <a href="edit_resume.php" class="btn btn-warning">Cancel</a>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Display Item -->
                        <div class="list-item">
                            <div class="list-item-header">
                                <div class="list-item-content">
                                    <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                                    <?php if ($project['period']): ?>
                                        <p><strong><?php echo htmlspecialchars($project['period']); ?></strong></p>
                                    <?php endif; ?>
                                    <p style="white-space: pre-line;"><?php echo htmlspecialchars($project['description']); ?></p>
                                </div>
                                <div class="list-item-actions">
                                    <a href="?edit_project=<?php echo $project['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_project">
                                        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')">×</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php if (empty($resume_data['projects'])): ?>
                    <p style="color: #6c757d; text-align: center; padding: 20px;">No projects yet. Add your first one above!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Skills Section -->
        <div class="section-card">
            <h2>Skills</h2>
            
            <button type="button" class="collapsible">+ Add New Skill</button>
            <div class="collapsible-content">
                <form method="POST" id="skill-form">
                    <input type="hidden" name="action" value="add_skill">
                    
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <input type="text" id="category" name="category" required minlength="2" maxlength="100" 
                               placeholder="e.g., Programming Languages, Web Technologies">
                        <div class="error-message" id="category_error">Category must be at least 2 characters long</div>
                        <small>Skills will be grouped by category</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="skill_name">Skill Name *</label>
                        <input type="text" id="skill_name" name="skill_name" required minlength="2" maxlength="100" 
                               placeholder="e.g., JavaScript, React">
                        <div class="error-message" id="skill_name_error">Skill name must be at least 2 characters long</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="skill_display_order">Display Order</label>
                        <input type="number" id="skill_display_order" name="display_order" value="0" min="0">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Add Skill</button>
                </form>
            </div>
            
            <div class="item-list">
                <?php foreach ($resume_data['skills'] as $category => $skills): ?>
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #2c3e50; margin-bottom: 10px; font-size: 1.1em;"><?php echo htmlspecialchars($category); ?></h4>
                        <?php foreach ($skills as $skill): ?>
                            <div class="list-item">
                                <div class="list-item-header">
                                    <div class="list-item-content">
                                        <p style="color: #2c3e50; font-weight: 500;"><?php echo htmlspecialchars($skill['skill_name']); ?></p>
                                    </div>
                                    <div class="list-item-actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_skill">
                                            <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this skill?')">×</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($resume_data['skills'])): ?>
                    <p style="color: #6c757d; text-align: center; padding: 20px;">No skills yet. Add your first one above!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Awards Section -->
        <div class="section-card">
            <h2>Awards & Activities</h2>
            
            <button type="button" class="collapsible">+ Add New Award</button>
            <div class="collapsible-content">
                <form method="POST" id="award-form">
                    <input type="hidden" name="action" value="add_award">
                    
                    <div class="form-group">
                        <label for="award_name">Award/Activity *</label>
                        <input type="text" id="award_name" name="award_name" required minlength="3" maxlength="200" 
                               placeholder="e.g., Dean's Lister (1st year - 2nd year)">
                        <div class="error-message" id="award_name_error">Award/activity must be at least 3 characters long</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="award_display_order">Display Order</label>
                        <input type="number" id="award_display_order" name="display_order" value="0" min="0">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Add Award</button>
                </form>
            </div>
            
            <div class="item-list">
                <?php foreach ($resume_data['awards'] as $award): ?>
                    <div class="list-item">
                        <div class="list-item-header">
                            <div class="list-item-content">
                                <p style="color: #2c3e50;"><?php echo htmlspecialchars($award['award_name']); ?></p>
                            </div>
                            <div class="list-item-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_award">
                                    <input type="hidden" name="id" value="<?php echo $award['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this award?')">×</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($resume_data['awards'])): ?>
                    <p style="color: #6c757d; text-align: center; padding: 20px;">No awards yet. Add your first one above!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Collapsible sections
        var coll = document.getElementsByClassName("collapsible");
        for (var i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    content.classList.remove("active");
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    content.classList.add("active");
                }
            });
        }
        
        // Auto-dismiss success messages after 5 seconds
        const messages = document.querySelectorAll('.message.success');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            }, 5000);
        });

        // Location dropdown functionality
        document.getElementById('country').addEventListener('change', function() {
            const country = this.value;
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            
            // Clear and disable dependent dropdowns
            provinceSelect.innerHTML = '<option value="">Select State/Province</option>';
            citySelect.innerHTML = '<option value="">Select City</option>';
            provinceSelect.disabled = !country;
            citySelect.disabled = true;
            
            if (country) {
                // In a real application, you would fetch this data from the server
                // For this example, we'll use the data from PHP
                const provinces = <?php echo json_encode($provinces); ?>;
                
                if (provinces[country]) {
                    for (const [code, name] of Object.entries(provinces[country])) {
                        const option = document.createElement('option');
                        option.value = code;
                        option.textContent = name;
                        provinceSelect.appendChild(option);
                    }
                    provinceSelect.disabled = false;
                }
            }
        });

        document.getElementById('province').addEventListener('change', function() {
            const province = this.value;
            const citySelect = document.getElementById('city');
            
            // Clear and disable city dropdown
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = !province;
            
            if (province) {
                // In a real application, you would fetch this data from the server
                const cities = <?php echo json_encode($cities); ?>;
                
                if (cities[province]) {
                    for (const [code, name] of Object.entries(cities[province])) {
                        const option = document.createElement('option');
                        option.value = code;
                        option.textContent = name;
                        citySelect.appendChild(option);
                    }
                    citySelect.disabled = false;
                }
            }
        });

        // Form validation
        function setupFormValidation(formId, validations) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                for (const [fieldId, rules] of Object.entries(validations)) {
                    const field = document.getElementById(fieldId);
                    const errorElement = document.getElementById(fieldId + '_error');
                    
                    if (field && errorElement) {
                        let fieldValid = true;
                        let errorMessage = '';
                        
                        if (rules.required && !field.value.trim()) {
                            fieldValid = false;
                            errorMessage = rules.required;
                        } else if (rules.minLength && field.value.trim().length < rules.minLength) {
                            fieldValid = false;
                            errorMessage = rules.minLength;
                        } else if (rules.pattern && !rules.pattern.test(field.value)) {
                            fieldValid = false;
                            errorMessage = rules.patternMessage;
                        }
                        
                        if (!fieldValid) {
                            isValid = false;
                            errorElement.style.display = 'block';
                            errorElement.textContent = errorMessage;
                            field.style.borderColor = '#dc3545';
                        } else {
                            errorElement.style.display = 'none';
                            field.style.borderColor = '#dee2e6';
                        }
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        }

        // Set up validation for each form
        setupFormValidation('user-info-form', {
            'full_name': {
                required: 'Full name is required',
                minLength: 'Name must be at least 2 characters long'
            },
            'email': {
                required: 'Email is required',
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                patternMessage: 'Please enter a valid email address'
            }
        });

        setupFormValidation('profile-form', {
            'title': {
                required: 'Professional title is required',
                minLength: 'Title must be at least 3 characters long'
            },
            'phone': {
                minLength: 'Phone number must be at least 10 digits'
            },
            'summary': {
                required: 'Professional summary is required',
                minLength: 'Summary must be at least 50 characters long'
            },
            'languages': {
                minLength: 'Please enter at least one language'
            }
        });

        setupFormValidation('education-form', {
            'degree': {
                required: 'Degree is required',
                minLength: 'Degree must be at least 3 characters long'
            },
            'school': {
                required: 'School/University is required',
                minLength: 'School name must be at least 2 characters long'
            }
        });

        setupFormValidation('project-form', {
            'project_title': {
                required: 'Project title is required',
                minLength: 'Project title must be at least 3 characters long'
            },
            'description': {
                required: 'Project description is required',
                minLength: 'Description must be at least 10 characters long'
            }
        });

        setupFormValidation('skill-form', {
            'category': {
                required: 'Category is required',
                minLength: 'Category must be at least 2 characters long'
            },
            'skill_name': {
                required: 'Skill name is required',
                minLength: 'Skill name must be at least 2 characters long'
            }
        });

        setupFormValidation('award-form', {
            'award_name': {
                required: 'Award/Activity is required',
                minLength: 'Award/Activity must be at least 3 characters long'
            }
        });
    </script>
</body>
</html>