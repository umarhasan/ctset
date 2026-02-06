<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfileTab;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tabs = $user->tabs()->get();
        return view('profile.index', compact('user','tabs'));
    }

     public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'signature_image' => 'nullable|image|max:2048',
        ]);

        // ================= PASSWORD =================
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // ================= PROFILE IMAGE =================
        if ($request->hasFile('profile_image')) {

            // Delete old file
            if ($user->profile_image && Storage::disk('public')->exists('profiles/'.$user->profile_image)) {
                Storage::disk('public')->delete('profiles/'.$user->profile_image);
            }

            // Store new file
            $file = $request->file('profile_image');
            $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('profiles', $filename, 'public');

            $data['profile_image'] = $filename; // only filename saved
        }

        // ================= SIGNATURE IMAGE =================
        if ($request->hasFile('signature_image')) {

            // Delete old file
            if ($user->signature_image && Storage::disk('public')->exists('signatures/'.$user->signature_image)) {
                Storage::disk('public')->delete('signatures/'.$user->signature_image);
            }

            // Store new file
            $file = $request->file('signature_image');
            $filename = 'signature_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('signatures', $filename, 'public');

            $data['signature_image'] = $filename;
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    // ================= STREAM PROFILE IMAGE =================
    public function streamProfileImage($filename)
    {
        $filePath = 'profiles/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Profile image not found');
        }

        return response()->file(storage_path('app/public/' . $filePath));
    }

    // ================= STREAM SIGNATURE IMAGE =================
    public function streamSignatureImage($filename)
    {
        $filePath = 'signatures/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Signature image not found');
        }

        return response()->file(storage_path('app/public/' . $filePath));
    }



    public function saveTab(Request $request)
    {
        $data = $request->validate([
            'tabid' => 'nullable|integer',
            'tabname' => 'required',
            'profile_type' => 'required|in:PU,PR',
            'tabsdesc' => 'nullable'
        ]);

        try {
            $tab = ProfileTab::updateOrCreate(
                ['id' => $request->tabid],
                [
                    'user_id' => auth()->id(),
                    'tabname' => $data['tabname'],
                    'profile_type' => $data['profile_type'],
                    'tabsdesc' => $data['tabsdesc']
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Tab saved successfully!',
                'tab' => $tab
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTab($id)
    {
        try {
            $tab = ProfileTab::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();
            
            if (!$tab) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tab not found or you do not have permission to delete it.'
                ], 404);
            }
            
            $tab->delete();
            
            // For AJAX requests, return JSON response
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tab deleted successfully!'
                ]);
            }
            
            return back()->with('success', 'Tab deleted successfully!');
            
        } catch (\Exception $e) {
            // For AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting tab: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting tab: ' . $e->getMessage());
        }
    }

    public function myProfile()
    {
        $user = auth()->user();

        $tabs = ProfileTab::where('user_id', $user->id)->get();
        return view('profile.my-profile', compact('user','tabs'));
    }

    public function publicProfile(Request $request)
    {
        $uid = $request->query('uid');

        $user = null;
        $tabs = collect(); // empty collection by default

        if($uid) {
            // Fetch user only if uid is provided
            $user = User::find($uid);
            if ($user) {
                $tabs = ProfileTab::where('user_id', $uid)->get();
            }
        }

        return view('profile.public-profile', compact('user', 'tabs'));
    }
}
