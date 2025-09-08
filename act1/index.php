<?php
$personalInfo = [
    'name' => 'Jan Mayen Mallen',
    'phone' => '+63 927 863 4850',
    'email' => 'mallenjanmayen@gmail.com',
    'address' => 'Calamba City, Laguna 4027'
];

$summary = "Computer Science student with strong experience in UI/UX design and frontend development, skilled in creating intuitive and responsive user interfaces. Proficient in JavaScript, React, HTML, CSS, and design tools such as Figma, with a solid foundation in programming and databases. Experienced in pitching and presenting technical ideas to both technical and non-technical audiences.";

$skills = [
    'Databases' => ['MySQL', 'PostgreSQL'],
    'Programming Languages' => ['Java', 'Python', 'C++', 'C#'],
    'Analytics' => ['Power BI'],
    'Web Technologies' => ['HTML', 'CSS', 'JavaScript', 'React', 'Node.js'],
    'Design' => ['UI/UX', 'Figma'],
    'Other' => ['Project Management', 'Data Visualization']
];

$projects = [
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
];

$education = [
    'degree' => 'Bachelor of Science in Computer Science',
    'institution' => 'Batangas State University, Batangas City',
    'period' => 'Aug 2023 - PRESENT'
];

$additionalInfo = [
    'languages' => ['English', 'Filipino'],
    'awards' => [
        'Dean\'s Lister (1st year - 2nd year)',
        'Technofusion 2025 – Hackathon – 2nd runner up'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $personalInfo['name']; ?> - CV</title>
    <style>
        @media print {
            .print-button {
                display: none;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="printCV()">📄 Print PDF</button>

    <h1><?php echo $personalInfo['name']; ?></h1>
    
    <p>
        <?php echo $personalInfo['phone']; ?> | 
        <a href="mailto:<?php echo $personalInfo['email']; ?>"><?php echo $personalInfo['email']; ?></a> | 
        <?php echo $personalInfo['address']; ?>
    </p>

    <hr>

    <h2>PROFESSIONAL SUMMARY</h2>
    <p><?php echo $summary; ?></p>

    <h2>SKILLS</h2>
    <?php foreach ($skills as $category => $skillList): ?>
        <p><strong><?php echo $category; ?>:</strong> <?php echo implode(', ', $skillList); ?></p>
    <?php endforeach; ?>

    <h2>PROJECTS</h2>
    <?php foreach ($projects as $project): ?>
        <h3><?php echo $project['title']; ?></h3>
        <p><em><?php echo $project['period']; ?></em></p>
        <ul>
            <?php foreach ($project['description'] as $point): ?>
                <li><?php echo $point; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>

    <h2>EDUCATION</h2>
    <p><strong><?php echo $education['degree']; ?></strong></p>
    <p><?php echo $education['institution']; ?></p>
    <p><em><?php echo $education['period']; ?></em></p>

    <h2>ADDITIONAL INFORMATION</h2>
    <p><strong>Languages:</strong> <?php echo implode(', ', $additionalInfo['languages']); ?></p>
    <p><strong>Awards & Activities:</strong></p>
    <ul>
        <?php foreach ($additionalInfo['awards'] as $award): ?>
            <li><?php echo $award; ?></li>
        <?php endforeach; ?>
    </ul>

    <script>
        function printCV() {
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
                printCV();
            }
        });
    </script>
</body>
</html>