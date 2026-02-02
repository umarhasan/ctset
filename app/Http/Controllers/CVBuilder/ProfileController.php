<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvProfile;
use App\Models\CvActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /* =========================
        STORE PROFILE
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'cv_id' => 'required|exists:cvs,id',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university' => 'nullable|string|max:255',
            'class_year' => 'nullable|string|max:50',
            'primary_interest' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $cv = Cv::with('user')->findOrFail($request->cv_id);
        $user = $cv->user;

        $data = $request->only([
            'full_name','email','university','class_year','primary_interest','phone'
        ]);

        /* ===== IMAGE UPLOAD ===== */
        if ($request->hasFile('profile_image')) {

            // Delete old file
            if ($user->profile_image && Storage::disk('public')->exists('profiles/'.$user->profile_image)) {
                Storage::disk('public')->delete('profiles/'.$user->profile_image);
            }

            // Store new file
            $file = $request->file('profile_image');
            $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('profiles', $filename, 'public');

            $data['profile_image'] = $filename;
        } else {
            $data['profile_image'] = $user->profile_image;
        }

        /* ===== UPDATE USER ===== */
        $user->update([
            'name' => $data['full_name'],
            'email' => $data['email'] ?? $user->email,
            'profile_image' => $data['profile_image'],
        ]);

        /* ===== CREATE CV PROFILE ===== */
        CvProfile::create(array_merge($data, ['cv_id' => $cv->id]));

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'Profile information added',
        ]);

        return redirect()
            ->route('cv.edit', $cv->id)
            ->with('success', 'Profile information saved successfully!');
    }

    /* =========================
        UPDATE PROFILE
    ========================== */
    public function update(Request $request, $id)
    {
        $profile = CvProfile::with('cv.user')->findOrFail($id);
        $user = $profile->cv->user;

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university' => 'nullable|string|max:255',
            'class_year' => 'nullable|string|max:50',
            'primary_interest' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'full_name','email','university','class_year','primary_interest','phone'
        ]);

        /* ===== IMAGE UPDATE ===== */
        if ($request->hasFile('profile_image')) {

            // Delete old file
            if ($user->profile_image && Storage::disk('public')->exists('profiles/'.$user->profile_image)) {
                Storage::disk('public')->delete('profiles/'.$user->profile_image);
            }

            // Store new file
            $file = $request->file('profile_image');
            $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('profiles', $filename, 'public');

            $data['profile_image'] = $filename;
        } else {
            $data['profile_image'] = $user->profile_image;
        }

        /* ===== UPDATE USER ===== */
        $user->update([
            'name' => $data['full_name'],
            'email' => $data['email'] ?? $user->email,
            'profile_image' => $data['profile_image'],
        ]);

        /* ===== UPDATE CV PROFILE ===== */
        $profile->update($data + ['profile_image' => $data['profile_image']]);

        CvActivity::create([
            'cv_id' => $profile->cv_id,
            'activity' => 'Profile information updated',
        ]);

        return redirect()
            ->route('cv.edit', $profile->cv_id)
            ->with('success', 'Profile updated successfully!');
    }

    /* =========================
        STREAM IMAGE
    ========================== */
    public function streamProfileImage($filename)
    {
        $filePath = 'profiles/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Profile image not found');
        }

        return response()->file(storage_path('app/public/' . $filePath));
    }
}
