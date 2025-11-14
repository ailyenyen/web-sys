<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Education;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Award;
use Illuminate\Support\Facades\Hash;

class ResumeSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $user = User::create([
            'name' => 'admin',
            'full_name' => 'Jan Mayen Mallen',
            'email' => 'mallenjanmayen@gmail.com',
            'password' => Hash::make('1234'),
            'is_active' => true,
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
            'title' => 'Computer Science Student',
            'phone' => '+63 927 863 4850',
            'location' => 'Calamba City, Laguna 4027',
            'linkedin' => '',
            'github' => '',
            'summary' => 'Computer Science student with strong experience in UI/UX design and frontend development, skilled in creating intuitive and responsive user interfaces. Proficient in JavaScript, React, HTML, CSS, and design tools such as Figma, with a solid foundation in programming and databases. Experienced in pitching and presenting technical ideas to both technical and non-technical audiences.',
            'languages' => 'English, Filipino',
        ]);

        // Create education
        Education::create([
            'user_id' => $user->id,
            'degree' => 'Bachelor of Science in Computer Science',
            'school' => 'Batangas State University, Batangas City',
            'start_date' => 'Aug 2023',
            'end_date' => 'PRESENT',
            'gpa' => null,
            'display_order' => 0,
        ]);

        // Create projects
        $projects = [
            [
                'title' => 'LenLens - Intelligent Classifier with Geolocation Support',
                'period' => 'April 2025 – May 2025',
                'description' => "Machine Learning Model to classify waste into four categories.\nIntegrated geolocation services and designed an intuitive, responsive UI for desktop and mobile platforms.\nPython with Flask, TensorFlow (MobileNetV2), and leaflet.js.",
                'display_order' => 0,
            ],
            [
                'title' => 'UNISTEEL',
                'period' => 'May 2025 - Aug 2025',
                'description' => "Designed a platform to prevent, manage, and recover from disasters like floods, earthquakes, and fires.\nIntegrated IoT sensors to collect environmental and structural data.\nUsed AI to analyze risks and provide real-time alerts and guidance.",
                'display_order' => 1,
            ],
            [
                'title' => 'CIVILIAN',
                'period' => 'May 2025 - Sep 2025',
                'description' => "Developed complex SQL queries to retrieve data from multiple tables for reporting purposes.\nCollaborated with developers in designing efficient database solutions based on business needs.\nCreated stored procedures and triggers to automate data manipulation processes.",
                'display_order' => 2,
            ],
        ];

        foreach ($projects as $project) {
            Project::create(array_merge($project, ['user_id' => $user->id]));
        }

        // Create skills
        $skills = [
            ['category' => 'Programming Languages', 'skill_name' => 'Java', 'display_order' => 0],
            ['category' => 'Programming Languages', 'skill_name' => 'Python', 'display_order' => 1],
            ['category' => 'Programming Languages', 'skill_name' => 'C++', 'display_order' => 2],
            ['category' => 'Programming Languages', 'skill_name' => 'C#', 'display_order' => 3],
            ['category' => 'Web Technologies', 'skill_name' => 'HTML', 'display_order' => 0],
            ['category' => 'Web Technologies', 'skill_name' => 'CSS', 'display_order' => 1],
            ['category' => 'Web Technologies', 'skill_name' => 'JavaScript', 'display_order' => 2],
            ['category' => 'Web Technologies', 'skill_name' => 'React', 'display_order' => 3],
            ['category' => 'Web Technologies', 'skill_name' => 'Node.js', 'display_order' => 4],
            ['category' => 'Databases', 'skill_name' => 'MySQL', 'display_order' => 0],
            ['category' => 'Databases', 'skill_name' => 'PostgreSQL', 'display_order' => 1],
            ['category' => 'Design & Analytics', 'skill_name' => 'UI/UX', 'display_order' => 0],
            ['category' => 'Design & Analytics', 'skill_name' => 'Figma', 'display_order' => 1],
            ['category' => 'Design & Analytics', 'skill_name' => 'Power BI', 'display_order' => 2],
            ['category' => 'Other Skills', 'skill_name' => 'Project Management', 'display_order' => 0],
            ['category' => 'Other Skills', 'skill_name' => 'Data Visualization', 'display_order' => 1],
        ];

        foreach ($skills as $skill) {
            Skill::create(array_merge($skill, ['user_id' => $user->id]));
        }

        // Create awards
        $awards = [
            ['award_name' => "Dean's Lister (1st year - 2nd year)", 'display_order' => 0],
            ['award_name' => 'Technofusion 2025 – Hackathon – 2nd runner up', 'display_order' => 1],
        ];

        foreach ($awards as $award) {
            Award::create(array_merge($award, ['user_id' => $user->id]));
        }

        $this->command->info('Resume data seeded successfully!');
    }
}
