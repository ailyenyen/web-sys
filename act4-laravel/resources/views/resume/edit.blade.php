@extends('layouts.app')

@section('title', 'Edit Resume - Dashboard')

@section('content')
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

    .top-nav-links a, .top-nav-links button {
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 4px;
        transition: background-color 0.3s;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1em;
    }

    .top-nav-links a:hover, .top-nav-links button:hover {
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

    @media (max-width: 768px) {
        .form-row {
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

<div class="top-nav">
    <h1>Resume Dashboard</h1>
    <div class="top-nav-links">
        <a href="{{ route('resume.show') }}">View Resume</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="message error">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <!-- User Information -->
    <div class="section-card">
        <h2>User Information</h2>
        <form method="POST" action="{{ route('profile.user-info') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                           required minlength="2" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save User Info</button>
        </form>
    </div>

    <!-- Professional Information -->
    <div class="section-card">
        <h2>Professional Information</h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Professional Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $user->profile->title ?? '') }}"
                           required minlength="3" maxlength="255">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}"
                           minlength="10" maxlength="50">
                </div>
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" value="{{ old('location', $user->profile->location ?? '') }}"
                       required minlength="2" maxlength="255" placeholder="e.g., Manila, Philippines">
                <small>Enter your city and country (this will be displayed on your resume)</small>
            </div>

            <div class="form-group">
                <label for="summary">Professional Summary *</label>
                <textarea id="summary" name="summary" required minlength="50" maxlength="1000">{{ old('summary', $user->profile->summary ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="languages">Languages (comma-separated)</label>
                <input type="text" id="languages" name="languages" value="{{ old('languages', $user->profile->languages ?? '') }}"
                       placeholder="English, Filipino, Spanish" minlength="2" maxlength="200">
            </div>

            <button type="submit" class="btn btn-primary">Save Professional Info</button>
        </form>
    </div>

    <!-- Education Section -->
    <div class="section-card">
        <h2>Education</h2>

        <button type="button" class="collapsible">Add New Education</button>
        <div class="collapsible-content">
            <form method="POST" action="{{ route('education.add') }}">
                @csrf

                <div class="form-group">
                    <label for="degree">Degree *</label>
                    <input type="text" id="degree" name="degree" required minlength="3" maxlength="255"
                           placeholder="e.g., Bachelor of Science in Computer Science">
                </div>

                <div class="form-group">
                    <label for="school">School/University *</label>
                    <input type="text" id="school" name="school" required minlength="2" maxlength="255">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" id="start_date" name="start_date" placeholder="e.g., Aug 2023" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" name="end_date" placeholder="e.g., Present or May 2027" maxlength="50">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gpa">GPA (optional)</label>
                    <input type="text" id="gpa" name="gpa" placeholder="e.g., 3.8/4.0" maxlength="20">
                </div>

                <div class="form-group">
                    <label for="display_order">Display Order</label>
                    <input type="number" id="display_order" name="display_order" value="0" min="0">
                    <small>Lower numbers appear first</small>
                </div>

                <button type="submit" class="btn btn-success">Add Education</button>
            </form>
        </div>

        <div class="item-list">
            @forelse($user->education as $edu)
                <div class="list-item">
                    <div class="list-item-header">
                        <div class="list-item-content">
                            <h4>{{ $edu->degree }}</h4>
                            <p><strong>{{ $edu->school }}</strong></p>
                            <p>{{ $edu->start_date }} - {{ $edu->end_date }}</p>
                            @if($edu->gpa)
                                <p>GPA: {{ $edu->gpa }}</p>
                            @endif
                        </div>
                        <div class="list-item-actions">
                            <form method="POST" action="{{ route('education.delete', $edu->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this education entry?')">×</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: #6c757d; text-align: center; padding: 20px;">No education entries yet. Add your first one above!</p>
            @endforelse
        </div>
    </div>

    <!-- Projects Section -->
    <div class="section-card">
        <h2>Projects</h2>

        <button type="button" class="collapsible">Add New Project</button>
        <div class="collapsible-content">
            <form method="POST" action="{{ route('project.add') }}">
                @csrf

                <div class="form-group">
                    <label for="project_title">Project Title *</label>
                    <input type="text" id="project_title" name="title" required minlength="3" maxlength="255">
                </div>

                <div class="form-group">
                    <label for="period">Time Period</label>
                    <input type="text" id="period" name="period" placeholder="e.g., April 2025 - May 2025" maxlength="100">
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required minlength="10" maxlength="1000"
                              placeholder="Describe your project. Each line will be a bullet point."></textarea>
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
            @forelse($user->projects as $project)
                <div class="list-item">
                    <div class="list-item-header">
                        <div class="list-item-content">
                            <h4>{{ $project->title }}</h4>
                            @if($project->period)
                                <p><strong>{{ $project->period }}</strong></p>
                            @endif
                            <p style="white-space: pre-line;">{{ $project->description }}</p>
                        </div>
                        <div class="list-item-actions">
                            <form method="POST" action="{{ route('project.delete', $project->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')">×</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: #6c757d; text-align: center; padding: 20px;">No projects yet. Add your first one above!</p>
            @endforelse
        </div>
    </div>

    <!-- Skills Section -->
    <div class="section-card">
        <h2>Skills</h2>

        <button type="button" class="collapsible">Add New Skill</button>
        <div class="collapsible-content">
            <form method="POST" action="{{ route('skill.add') }}">
                @csrf

                <div class="form-group">
                    <label for="category">Category *</label>
                    <input type="text" id="category" name="category" required minlength="2" maxlength="100"
                           placeholder="e.g., Programming Languages, Web Technologies">
                    <small>Skills will be grouped by category</small>
                </div>

                <div class="form-group">
                    <label for="skill_name">Skill Name *</label>
                    <input type="text" id="skill_name" name="skill_name" required minlength="2" maxlength="100"
                           placeholder="e.g., JavaScript, React">
                </div>

                <div class="form-group">
                    <label for="skill_display_order">Display Order</label>
                    <input type="number" id="skill_display_order" name="display_order" value="0" min="0">
                </div>

                <button type="submit" class="btn btn-success">Add Skill</button>
            </form>
        </div>

        <div class="item-list">
            @php
                $groupedSkills = $user->skills->groupBy('category');
            @endphp
            @forelse($groupedSkills as $category => $skills)
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #2c3e50; margin-bottom: 10px; font-size: 1.1em;">{{ $category }}</h4>
                    @foreach($skills as $skill)
                        <div class="list-item">
                            <div class="list-item-header">
                                <div class="list-item-content">
                                    <p style="color: #2c3e50; font-weight: 500;">{{ $skill->skill_name }}</p>
                                </div>
                                <div class="list-item-actions">
                                    <form method="POST" action="{{ route('skill.delete', $skill->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this skill?')">×</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p style="color: #6c757d; text-align: center; padding: 20px;">No skills yet. Add your first one above!</p>
            @endforelse
        </div>
    </div>

    <!-- Awards Section -->
    <div class="section-card">
        <h2>Awards & Activities</h2>

        <button type="button" class="collapsible">Add New Award</button>
        <div class="collapsible-content">
            <form method="POST" action="{{ route('award.add') }}">
                @csrf

                <div class="form-group">
                    <label for="award_name">Award/Activity *</label>
                    <input type="text" id="award_name" name="award_name" required minlength="3" maxlength="255"
                           placeholder="e.g., Dean's Lister (1st year - 2nd year)">
                </div>

                <div class="form-group">
                    <label for="award_display_order">Display Order</label>
                    <input type="number" id="award_display_order" name="display_order" value="0" min="0">
                </div>

                <button type="submit" class="btn btn-success">Add Award</button>
            </form>
        </div>

        <div class="item-list">
            @forelse($user->awards as $award)
                <div class="list-item">
                    <div class="list-item-header">
                        <div class="list-item-content">
                            <p style="color: #2c3e50;">{{ $award->award_name }}</p>
                        </div>
                        <div class="list-item-actions">
                            <form method="POST" action="{{ route('award.delete', $award->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this award?')">×</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: #6c757d; text-align: center; padding: 20px;">No awards yet. Add your first one above!</p>
            @endforelse
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
</script>
@endsection
