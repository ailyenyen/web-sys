<?php
require_once 'config/database.php';
require_once 'classes/Resume.php';

// IMPORTANT: This always shows user_id = 1 (the shared resume)
// The ID parameter is kept for future expansion but currently ignored
$user_id = 1; // Always show the shared resume

// Create resume object
$resume = new Resume($pdo, $user_id);
$resume_data = $resume->getCompleteResume();
$profile = $resume_data['profile'];

// Check if resume exists
if (!$profile || !$profile['full_name']) {
    die('Resume not found. Please ensure the admin user and resume data exist in the database.');
}

// Convert languages string to array
$languages = !empty($profile['languages']) ? explode(',', $profile['languages']) : [];
$languages = array_map('trim', $languages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile['full_name']); ?> - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #f8f9fa;
        }

        .public-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .public-banner h3 {
            font-size: 1.1em;
            font-weight: 500;
        }

        .public-banner p {
            font-size: 0.9em;
            margin-top: 5px;
            opacity: 0.95;
        }

        .public-banner a {
            color: white;
            text-decoration: underline;
            font-weight: 600;
        }

        .resume-container {
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #ffffff;
            padding: 40px;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #2c3e50;
            font-weight: 600;
        }

        .header .title {
            font-size: 1.3em;
            color: #6c757d;
            margin-bottom: 25px;
            font-weight: 400;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            font-size: 0.95em;
        }

        .contact-item a {
            color: #495057;
            text-decoration: none;
        }

        .contact-item a:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .content {
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .left-column, .right-column {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .section {
            background-color: #ffffff;
            padding: 25px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .section h2 {
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }

        .section p {
            color: #495057;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .item {
            margin-bottom: 25px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #6c757d;
        }

        .item:last-child {
            margin-bottom: 0;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .item-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }

        .item-subtitle {
            color: #6c757d;
            font-style: italic;
            margin-top: 5px;
            font-size: 0.95em;
        }

        .date-badge {
            background-color: #6c757d;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
            white-space: nowrap;
        }

        .item-description {
            color: #495057;
            white-space: pre-line;
            line-height: 1.7;
        }

        .item-description ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        .item-description ul li {
            margin-bottom: 8px;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .skill-category h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1em;
            font-weight: 600;
        }

        .skill-list {
            list-style: none;
        }

        .skill-list li {
            background-color: #f1f3f4;
            margin-bottom: 8px;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 0.95em;
            color: #495057;
            transition: background-color 0.3s;
        }

        .skill-list li:hover {
            background-color: #e9ecef;
        }

        .awards-list {
            list-style: none;
        }

        .awards-list li {
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border-left: 3px solid #28a745;
            border-radius: 5px;
            color: #495057;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 30px;
            }
            
            .skills-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .public-banner {
                padding: 15px;
            }
            
            .resume-container {
                margin: 20px 10px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .header .title {
                font-size: 1.1em;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .section {
                padding: 20px 15px;
            }
            
            .item-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Print Styles */
        @media print {
            .public-banner {
                display: none;
            }
            
            body {
                background-color: white;
            }
            
            .resume-container {
                box-shadow: none;
                margin: 0;
            }
            
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="public-banner">
        <h3>🌐 Public Resume View</h3>
        <p>This is a public resume. Want to create your own? <a href="signup.php">Sign up here</a></p>
    </div>

    <div class="resume-container">
        <!-- Header Section -->
        <header class="header">
            <h1><?php echo htmlspecialchars($profile['full_name']); ?></h1>
            <div class="title"><?php echo htmlspecialchars($profile['title'] ?? 'Professional'); ?></div>
            
            <div class="contact-info">
                <?php if ($profile['email']): ?>
                    <div class="contact-item">
                        <span>📧</span>
                        <a href="mailto:<?php echo htmlspecialchars($profile['email']); ?>">
                            <?php echo htmlspecialchars($profile['email']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($profile['phone']): ?>
                    <div class="contact-item">
                        <span>📱</span>
                        <span><?php echo htmlspecialchars($profile['phone']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($profile['location']): ?>
                    <div class="contact-item">
                        <span>📍</span>
                        <span><?php echo htmlspecialchars($profile['location']); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($profile['linkedin']): ?>
                    <div class="contact-item">
                        <span>💼</span>
                        <a href="<?php echo htmlspecialchars($profile['linkedin']); ?>" target="_blank">LinkedIn</a>
                    </div>
                <?php endif; ?>
                
                <?php if ($profile['github']): ?>
                    <div class="contact-item">
                        <span>💻</span>
                        <a href="<?php echo htmlspecialchars($profile['github']); ?>" target="_blank">GitHub</a>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <div class="content">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Summary Section -->
                <?php if ($profile['summary']): ?>
                    <section class="section">
                        <h2>Professional Summary</h2>
                        <p><?php echo nl2br(htmlspecialchars($profile['summary'])); ?></p>
                    </section>
                <?php endif; ?>

                <!-- Projects Section -->
                <?php if (!empty($resume_data['projects'])): ?>
                    <section class="section">
                        <h2>Projects</h2>
                        <?php foreach ($resume_data['projects'] as $project): ?>
                            <div class="item">
                                <div class="item-header">
                                    <div>
                                        <div class="item-title"><?php echo htmlspecialchars($project['title']); ?></div>
                                    </div>
                                    <?php if ($project['period']): ?>
                                        <div class="date-badge"><?php echo htmlspecialchars($project['period']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="item-description">
                                    <?php 
                                    // Split description by newlines and create bullet points
                                    $lines = explode("\n", trim($project['description']));
                                    if (count($lines) > 1): ?>
                                        <ul>
                                            <?php foreach ($lines as $line): ?>
                                                <?php if (trim($line)): ?>
                                                    <li><?php echo htmlspecialchars(trim($line)); ?></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($project['description']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Skills Section -->
                <?php if (!empty($resume_data['skills'])): ?>
                    <section class="section">
                        <h2>Skills & Technologies</h2>
                        <div class="skills-grid">
                            <?php foreach ($resume_data['skills'] as $category => $skills): ?>
                                <div class="skill-category">
                                    <h4><?php echo htmlspecialchars($category); ?></h4>
                                    <ul class="skill-list">
                                        <?php foreach ($skills as $skill): ?>
                                            <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Education Section -->
                <?php if (!empty($resume_data['education'])): ?>
                    <section class="section">
                        <h2>Education</h2>
                        <?php foreach ($resume_data['education'] as $edu): ?>
                            <div class="item">
                                <div class="item-header">
                                    <div>
                                        <div class="item-title"><?php echo htmlspecialchars($edu['degree']); ?></div>
                                        <div class="item-subtitle"><?php echo htmlspecialchars($edu['school']); ?></div>
                                    </div>
                                    <?php if ($edu['start_date'] || $edu['end_date']): ?>
                                        <div class="date-badge">
                                            <?php echo htmlspecialchars($edu['start_date']); ?>
                                            <?php if ($edu['end_date']): ?>
                                                - <?php echo htmlspecialchars($edu['end_date']); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($edu['gpa']): ?>
                                    <p style="color: #495057; margin-top: 8px;"><strong>GPA:</strong> <?php echo htmlspecialchars($edu['gpa']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <!-- Additional Information Section -->
                <?php if (!empty($languages) || !empty($resume_data['awards'])): ?>
                    <section class="section">
                        <h2>Additional Information</h2>
                        
                        <?php if (!empty($languages)): ?>
                            <div style="margin-bottom: 20px;">
                                <h4 style="color: #2c3e50; margin-bottom: 10px; font-weight: 600;">Languages</h4>
                                <p style="color: #495057;"><?php echo implode(', ', array_map('htmlspecialchars', $languages)); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($resume_data['awards'])): ?>
                            <div>
                                <h4 style="color: #2c3e50; margin-bottom: 10px; font-weight: 600;">Awards & Activities</h4>
                                <ul class="awards-list">
                                    <?php foreach ($resume_data['awards'] as $award): ?>
                                        <li><?php echo htmlspecialchars($award['award_name']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>