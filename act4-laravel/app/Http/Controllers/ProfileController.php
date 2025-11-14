<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Education;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Award;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $userId = 1; // Shared resume

    public function updateUserInfo(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|min:2|max:100',
            'email' => 'required|email',
        ]);

        User::find($this->userId)->update($validated);

        return back()->with('success', 'User information updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'phone' => 'nullable|min:10|max:50',
            'location' => 'required|min:2|max:255',
            'linkedin' => 'nullable|max:255',
            'github' => 'nullable|max:255',
            'summary' => 'required|min:50|max:1000',
            'languages' => 'nullable|min:2|max:200',
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $this->userId],
            $validated
        );

        return back()->with('success', 'Profile updated successfully!');
    }

    public function addEducation(Request $request)
    {
        $validated = $request->validate([
            'degree' => 'required|min:3|max:255',
            'school' => 'required|min:2|max:255',
            'start_date' => 'nullable|max:50',
            'end_date' => 'nullable|max:50',
            'gpa' => 'nullable|max:20',
            'display_order' => 'integer',
        ]);

        Education::create([
            'user_id' => $this->userId,
            ...$validated
        ]);

        return back()->with('success', 'Education added successfully!');
    }

    public function updateEducation(Request $request, $id)
    {
        $validated = $request->validate([
            'degree' => 'required|min:3|max:255',
            'school' => 'required|min:2|max:255',
            'start_date' => 'nullable|max:50',
            'end_date' => 'nullable|max:50',
            'gpa' => 'nullable|max:20',
            'display_order' => 'integer',
        ]);

        Education::where('id', $id)
            ->where('user_id', $this->userId)
            ->update($validated);

        return back()->with('success', 'Education updated successfully!');
    }

    public function deleteEducation($id)
    {
        Education::where('id', $id)
            ->where('user_id', $this->userId)
            ->delete();

        return back()->with('success', 'Education deleted successfully!');
    }

    public function addProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'period' => 'nullable|max:100',
            'description' => 'required|min:10|max:1000',
            'display_order' => 'integer',
        ]);

        Project::create([
            'user_id' => $this->userId,
            ...$validated
        ]);

        return back()->with('success', 'Project added successfully!');
    }

    public function updateProject(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'period' => 'nullable|max:100',
            'description' => 'required|min:10|max:1000',
            'display_order' => 'integer',
        ]);

        Project::where('id', $id)
            ->where('user_id', $this->userId)
            ->update($validated);

        return back()->with('success', 'Project updated successfully!');
    }

    public function deleteProject($id)
    {
        Project::where('id', $id)
            ->where('user_id', $this->userId)
            ->delete();

        return back()->with('success', 'Project deleted successfully!');
    }

    public function addSkill(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|min:2|max:100',
            'skill_name' => 'required|min:2|max:100',
            'display_order' => 'integer',
        ]);

        Skill::create([
            'user_id' => $this->userId,
            ...$validated
        ]);

        return back()->with('success', 'Skill added successfully!');
    }

    public function deleteSkill($id)
    {
        Skill::where('id', $id)
            ->where('user_id', $this->userId)
            ->delete();

        return back()->with('success', 'Skill deleted successfully!');
    }

    public function addAward(Request $request)
    {
        $validated = $request->validate([
            'award_name' => 'required|min:3|max:255',
            'display_order' => 'integer',
        ]);

        Award::create([
            'user_id' => $this->userId,
            ...$validated
        ]);

        return back()->with('success', 'Award added successfully!');
    }

    public function deleteAward($id)
    {
        Award::where('id', $id)
            ->where('user_id', $this->userId)
            ->delete();

        return back()->with('success', 'Award deleted successfully!');
    }
}
