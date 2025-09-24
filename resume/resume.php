<?php
require_once 'config/database.php';

// Check if user is logged in for full access
$is_logged_in = isLoggedIn();
$show_contact_info = $is_logged_in; // Hide contact info if not logged in

// Resume data structure
$resume_data = [
    'personal_info' => [
        'name' => 'Jan Mayen Mallen',
        'title' => 'Computer Science Student',
        'email' => 'mallenjanmayen@gmail.com',
        'phone' => '+63 927 863 4850',
        'location' => 'Calamba City, Laguna 4027',
        'linkedin' => '',
        'github' => ''
    ],
    'summary' => 'Computer Science student with strong experience in UI/UX design and frontend development, skilled in creating intuitive and responsive user interfaces. Proficient in JavaScript, React, HTML, CSS, and design tools such as Figma, with a solid foundation in programming and databases. Experienced in pitching and presenting technical ideas to both technical and non-technical audiences.',
    'projects' => [
        [
            'title' => 'LenLens - Intelligent Classifier with Geolocation Support',
            'period' => 'April 2025 – May 2025',
            'description' => [
                'Machine Learning Model to classify waste into four categories.',
                'Integrated geolocation services and designed an intuitive, responsive UI for desktop and mobile platforms.',
                'Python with Flask, TensorFlow (MobileNetV2), and leaflet.js.'
            ]
        ],
        [
            'title' => 'UNISTEEL',
            'period' => 'May 2025 - Aug 2025',
            'description' => [
                'Designed a platform to prevent, manage, and recover from disasters like floods, earthquakes, and fires.',
                'Integrated IoT sensors to collect environmental and structural data.',
                'Used AI to analyze risks and provide real-time alerts and guidance.'
            ]
        ],
        [
            'title' => 'CIVILIAN',
            'period' => 'May 2025 - Sep 2025',
            'description' => [
                'Developed complex SQL queries to retrieve data from multiple tables for reporting purposes.',
                'Collaborated with developers in designing efficient database solutions based on business needs.',
                'Created stored procedures and triggers to automate data manipulation processes.'
            ]
        ]
    ],
    'education' => [
        [
            'degree' => 'Bachelor of Science in Computer Science',
            'school' => 'Batangas State University, Batangas City',
            'date' => 'Aug 2023 - PRESENT',
            'gpa' => null
        ]
    ],
    'skills' => [
        'Programming Languages' => ['Java', 'Python', 'C++', 'C#'],
        'Web Technologies' => ['HTML', 'CSS', 'JavaScript', 'React', 'Node.js'],
        'Databases' => ['MySQL', 'PostgreSQL'],
        'Design & Analytics' => ['UI/UX', 'Figma', 'Power BI'],
        'Other Skills' => ['Project Management', 'Data Visualization']
    ],
    'additional_info' => [
        'languages' => ['English', 'Filipino'],
        'awards' => [
            'Dean\'s Lister (1st year - 2nd year)',
            'Technofusion 2025 – Hackathon – 2nd runner up'
        ]
    ]
];

function generateSkillLevel($skill) {
    // Simple logic to assign skill levels (in a real app, this would be data-driven)
    $high_skills = ['HTML', 'CSS', 'JavaScript', 'Java', 'Python', 'UI/UX'];
    $medium_skills = ['React', 'Node.js', 'MySQL', 'Figma'];
    
    if (in_array($skill, $high_skills)) return '90%';
    if (in_array($skill, $medium_skills)) return '75%';
    return '60%';
}

// Handle logout
if (isset($_GET['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($resume_data['personal_info']['name']); ?> - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #2c3e50;
            background-color: #f8f9fa;
            padding: 0;
            min-height: 100vh;
        }

        .resume-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-status {
            background-color: #495057;
            color: white;
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
        }

        .login-status .status-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .login-status .status-right {
            display: flex;
            gap: 15px;
        }

        .login-status a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .login-status a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .access-notice {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 40px;
            text-align: center;
            font-size: 0.9em;
            border-bottom: 1px solid #f5c6cb;
        }

        .access-notice.success {
            background-color: #d4edda;
            color: #155724;
            border-bottom: 1px solid #c3e6cb;
        }

        .header {
            background-color: #ffffff;
            color: #2c3e50;
            padding: 30px 40px 20px 40px;
            border-bottom: 2px solid #e9ecef;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2em;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .header .title {
            font-size: 1.1em;
            margin-bottom: 20px;
            color: #6c757d;
            font-weight: 400;
        }

        .print-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #495057;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
            background-color: #343a40;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9em;
            color: #495057;
        }

        .contact-hidden {
            color: #6c757d;
            font-style: italic;
        }

        .content {
            padding: 30px 40px;
            flex: 1;
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
            border-radius: 6px;
        }

        .section h2 {
            color: #2c3e50;
            font-size: 1.4em;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }

        .section h3 {
            color: #495057;
            font-size: 1.1em;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .section p, .section ul {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .job, .education-item {
            margin-bottom: 20px;
            padding: 18px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #6c757d;
        }

        .job-header, .education-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .job-title, .degree {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.05em;
        }

        .company, .school {
            color: #6c757d;
            font-style: italic;
            margin-top: 3px;
        }

        .date {
            background-color: #6c757d;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
            white-space: nowrap;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .skill-category h4 {
            color: #2c3e50;
            margin-bottom: 12px;
            font-size: 1.05em;
            font-weight: 600;
        }

        .skill-list {
            list-style: none;
        }

        .skill-list li {
            background-color: #f1f3f4;
            margin-bottom: 6px;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            color: #495057;
        }

        ul:not(.skill-list) {
            padding-left: 18px;
        }

        ul:not(.skill-list) li {
            margin-bottom: 8px;
            color: #495057;
        }

        ul:not(.skill-list) li::marker {
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 25px 30px;
            }
            
            .skills-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                background-color: white;
            }
            
            .resume-container {
                box-shadow: none;
            }
            
            .login-status {
                padding: 10px 20px;
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .access-notice {
                padding: 12px 20px;
            }
            
            .header {
                padding: 25px 20px 15px 20px;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
            
            .print-button {
                position: static;
                margin-bottom: 20px;
                width: 100%;
            }
            
            .contact-info {
                grid-template-columns: 1fr;
                gap: 10px;
                text-align: center;
            }
            
            .content {
                padding: 20px 15px;
                gap: 20px;
            }
            
            .section {
                padding: 20px 15px;
            }
            
            .job-header, .education-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .date {
                align-self: flex-start;
            }
        }

        /* Print Styles */
        @media print {
            .login-status, .access-notice, .print-button {
                display: none !important;
            }
            
            body {
                background-color: white;
                font-size: 11pt;
                line-height: 1.4;
            }
            
            .resume-container {
                box-shadow: none;
                max-width: none;
                min-height: auto;
            }
            
            .header {
                border-bottom: 1px solid #dee2e6;
                padding: 20px 30px 15px 30px;
            }
            
            .header h1 {
                font-size: 18pt;
                margin-bottom: 6px;
            }
            
            .header .title {
                font-size: 12pt;
                margin-bottom: 15px;
            }
            
            .contact-info {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                font-size: 9pt;
            }
            
            .content {
                padding: 20px 30px;
                gap: 25px;
            }
            
            .section {
                padding: 15px;
                border: 1px solid #dee2e6;
                margin-bottom: 15px;
                page-break-inside: avoid;
            }
            
            .section h2 {
                font-size: 13pt;
                margin-bottom: 12px;
            }
            
            .job, .education-item {
                padding: 12px;
                margin-bottom: 12px;
                page-break-inside: avoid;
            }
            
            .job-title, .degree {
                font-size: 11pt;
            }
            
            .company, .school {
                font-size: 10pt;
            }
            
            .date {
                font-size: 9pt;
                padding: 2px 8px;
            }
        }
    </style>
    <script>
        function printResume() {
            const originalTitle = document.title;
            document.title = 'Jan_Mayen_Mallen_CV';
            window.print();
            setTimeout(() => {
                document.title = originalTitle;
            }, 1000);
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printResume();
            }
        });
    </script>
</head>
<body>
    <div class="resume-container">
        <!-- Login Status Bar -->
        <div class="login-status">
            <div class="status-left">
                <?php if ($is_logged_in): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <span>Full access granted</span>
                <?php else: ?>
                    <span>👤 Viewing as Guest</span>
                    <span>Limited access - Contact information hidden</span>
                <?php endif; ?>
            </div>
            <div class="status-right">
                <?php if ($is_logged_in): ?>
                    <a href="?logout=1">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>


        <!-- Header Section -->
        <header class="header">
            <?php if ($is_logged_in): ?>
                <button class="print-button" onclick="printResume()"> Print / Download CV</button>
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($resume_data['personal_info']['name']); ?></h1>
            <div class="title"><?php echo htmlspecialchars($resume_data['personal_info']['title']); ?></div>
            
            <div class="contact-info">
                <div class="contact-item">
                    <span></span>
                    <?php if ($show_contact_info): ?>
                        <span><?php echo htmlspecialchars($resume_data['personal_info']['email']); ?></span>
                    <?php else: ?>
                        <span class="contact-hidden">Login to view email</span>
                    <?php endif; ?>
                </div>
                <div class="contact-item">
                    <span></span>
                    <?php if ($show_contact_info): ?>
                        <span><?php echo htmlspecialchars($resume_data['personal_info']['phone']); ?></span>
                    <?php else: ?>
                        <span class="contact-hidden">Login to view phone</span>
                    <?php endif; ?>
                </div>
                <div class="contact-item">
                    <span></span>
                    <?php if ($show_contact_info): ?>
                        <span><?php echo htmlspecialchars($resume_data['personal_info']['location']); ?></span>
                    <?php else: ?>
                        <span class="contact-hidden">Login to view location</span>
                    <?php endif; ?>
                </div>
            </div>
        </header>    

        <div class="content">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Summary Section -->
                <section class="section">
                    <h2>Professional Summary</h2>
                    <p><?php echo htmlspecialchars($resume_data['summary']); ?></p>
                </section>

                <!-- Projects Section -->
                <section class="section">
                    <h2>Projects</h2>
                    <?php foreach ($resume_data['projects'] as $project): ?>
                        <div class="job">
                            <div class="job-header">
                                <div>
                                    <div class="job-title"><?php echo htmlspecialchars($project['title']); ?></div>
                                </div>
                                <div class="date"><?php echo htmlspecialchars($project['period']); ?></div>
                            </div>
                            <ul>
                                <?php foreach ($project['description'] as $point): ?>
                                    <li><?php echo htmlspecialchars($point); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </section>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Skills Section -->
                <section class="section">
                    <h2>Skills & Technologies</h2>
                    <div class="skills-grid">
                        <?php foreach ($resume_data['skills'] as $category => $skills): ?>
                            <div class="skill-category">
                                <h4><?php echo htmlspecialchars($category); ?></h4>
                                <ul class="skill-list">
                                    <?php foreach ($skills as $skill): ?>
                                        <li><?php echo htmlspecialchars($skill); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Education Section -->
                <section class="section">
                    <h2>Education</h2>
                    <?php foreach ($resume_data['education'] as $education): ?>
                        <div class="education-item">
                            <div class="education-header">
                                <div>
                                    <div class="degree"><?php echo htmlspecialchars($education['degree']); ?></div>
                                    <div class="school"><?php echo htmlspecialchars($education['school']); ?></div>
                                </div>
                                <div class="date"><?php echo htmlspecialchars($education['date']); ?></div>
                            </div>
                            <?php if ($education['gpa']): ?>
                                <p><strong>GPA:</strong> <?php echo htmlspecialchars($education['gpa']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>

                <!-- Additional Information Section -->
                <section class="section">
                    <h2>Additional Information</h2>
                    <div style="margin-bottom: 15px;">
                        <h4 style="color: #2c3e50; margin-bottom: 8px; font-weight: 600;">Languages</h4>
                        <p style="color: #495057;"><?php echo implode(', ', $resume_data['additional_info']['languages']); ?></p>
                    </div>
                    <div>
                        <h4 style="color: #2c3e50; margin-bottom: 8px; font-weight: 600;">Awards & Activities</h4>
                        <ul>
                            <?php foreach ($resume_data['additional_info']['awards'] as $award): ?>
                                <li><?php echo htmlspecialchars($award); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>