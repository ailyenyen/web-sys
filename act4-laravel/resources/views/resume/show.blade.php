@extends('layouts.app')

@section('title', $user->full_name . ' - Resume')

@section('content')
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

    .header {
        background-color: #ffffff;
        color: #2c3e50;
        padding: 30px 40px 20px 40px;
        border-bottom: 2px solid #e9ecef;
        text-align: center;
        position: relative;
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

    .section p {
        margin-bottom: 15px;
        line-height: 1.6;
        color: #495057;
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

    .empty-state {
        color: #6c757d;
        text-align: center;
        padding: 20px;
    }

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
    }

    @media print {
        .login-status, .print-button {
            display: none !important;
        }

        body {
            background-color: white;
            font-size: 11pt;
        }

        .resume-container {
            box-shadow: none;
            max-width: none;
        }

        .header {
            border-bottom: 1px solid #dee2e6;
            padding: 20px 30px 15px 30px;
        }

        .section {
            page-break-inside: avoid;
        }
    }
</style>

<div class="resume-container">
    <!-- Login Status Bar -->
    <div class="login-status">
        <div class="status-left">
            @if($isLoggedIn)
                <span>Welcome, {{ Auth::user()->username }}!</span>
                <span>Full access granted</span>
            @else
                <span>Viewing as Guest</span>
                <span>Limited access - Contact information hidden</span>
            @endif
        </div>
        <div class="status-right">
            @if($isLoggedIn)
                <a href="{{ route('resume.edit') }}">Edit Resume</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; padding: 5px 10px; border-radius: 3px;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('signup') }}">Sign Up</a>
            @endif
        </div>
    </div>

    <!-- Header Section -->
    <header class="header">
        @if($isLoggedIn)
            <button class="print-button" onclick="printResume()">Print / Download CV</button>
        @endif
        <h1>{{ $user->full_name }}</h1>
        <div class="title">{{ $user->profile->title ?? 'Professional' }}</div>

        <div class="contact-info">
            <div class="contact-item">
                @if($isLoggedIn && $user->email)
                    <span>{{ $user->email }}</span>
                @else
                    <span class="contact-hidden">Login to view email</span>
                @endif
            </div>
            <div class="contact-item">
                @if($isLoggedIn && $user->profile && $user->profile->phone)
                    <span>{{ $user->profile->phone }}</span>
                @else
                    <span class="contact-hidden">Login to view phone</span>
                @endif
            </div>
            <div class="contact-item">
                @if($isLoggedIn && $user->profile && $user->profile->location)
                    <span>{{ $user->profile->location }}</span>
                @else
                    <span class="contact-hidden">Login to view location</span>
                @endif
            </div>
        </div>
    </header>

    <div class="content">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Summary Section -->
            @if($user->profile && $user->profile->summary)
                <section class="section">
                    <h2>Professional Summary</h2>
                    <p>{{ $user->profile->summary }}</p>
                </section>
            @endif

            <!-- Projects Section -->
            <section class="section">
                <h2>Projects</h2>
                @if($user->projects->count() > 0)
                    @foreach($user->projects as $project)
                        <div class="job">
                            <div class="job-header">
                                <div>
                                    <div class="job-title">{{ $project->title }}</div>
                                </div>
                                @if($project->period)
                                    <div class="date">{{ $project->period }}</div>
                                @endif
                            </div>
                            <ul>
                                @foreach(explode("\n", trim($project->description)) as $line)
                                    @if(trim($line))
                                        <li>{{ trim($line) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @else
                    <p class="empty-state">No projects available. Please add projects in the edit page.</p>
                @endif
            </section>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Skills Section -->
            <section class="section">
                <h2>Skills & Technologies</h2>
                @if($user->skills->count() > 0)
                    <div class="skills-grid">
                        @php
                            $groupedSkills = $user->skills->groupBy('category');
                        @endphp
                        @foreach($groupedSkills as $category => $skills)
                            <div class="skill-category">
                                <h4>{{ $category }}</h4>
                                <ul class="skill-list">
                                    @foreach($skills as $skill)
                                        <li>{{ $skill->skill_name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-state">No skills available. Please add skills in the edit page.</p>
                @endif
            </section>

            <!-- Education Section -->
            <section class="section">
                <h2>Education</h2>
                @if($user->education->count() > 0)
                    @foreach($user->education as $edu)
                        <div class="education-item">
                            <div class="education-header">
                                <div>
                                    <div class="degree">{{ $edu->degree }}</div>
                                    <div class="school">{{ $edu->school }}</div>
                                </div>
                                @if($edu->start_date || $edu->end_date)
                                    <div class="date">
                                        {{ $edu->start_date }}
                                        @if($edu->end_date)
                                            - {{ $edu->end_date }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if($edu->gpa)
                                <p><strong>GPA:</strong> {{ $edu->gpa }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="empty-state">No education available. Please add education in the edit page.</p>
                @endif
            </section>

            <!-- Additional Information Section -->
            @if(($user->profile && $user->profile->languages) || $user->awards->count() > 0)
                <section class="section">
                    <h2>Additional Information</h2>

                    @if($user->profile && $user->profile->languages)
                        <div style="margin-bottom: 15px;">
                            <h4 style="color: #2c3e50; margin-bottom: 8px; font-weight: 600;">Languages</h4>
                            <p style="color: #495057;">{{ $user->profile->languages }}</p>
                        </div>
                    @endif

                    @if($user->awards->count() > 0)
                        <div>
                            <h4 style="color: #2c3e50; margin-bottom: 8px; font-weight: 600;">Awards & Activities</h4>
                            <ul>
                                @foreach($user->awards as $award)
                                    <li>{{ $award->award_name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </section>
            @endif
        </div>
    </div>
</div>

<script>
    function printResume() {
        const originalTitle = document.title;
        document.title = '{{ str_replace(' ', '_', $user->full_name) }}_CV';
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
@endsection
