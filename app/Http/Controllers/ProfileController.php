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

        /* ================= VALIDATION ================= */
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'password'        => 'nullable|min:6',
            'profile_image'   => 'nullable|image|max:2048',
            'signature_image' => 'nullable|image|max:2048',
            'bio'             => 'nullable|string',
        ]);

        /* ================= PASSWORD ================= */
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        /* ================= PROFILE IMAGE → storage/app/public/profiles ================= */
        if ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $file = $request->file('profile_image');
            $filename = 'profile_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs('profiles', $filename, 'public');

            $data['profile_image'] = $path; // profiles/filename.jpg
        }

        /* ================= SIGNATURE IMAGE → storage/app/public/signatures ================= */
        if ($request->hasFile('signature_image')) {

            if ($user->signature_image) {
                Storage::disk('public')->delete($user->signature_image);
            }

            $file = $request->file('signature_image');
            $filename = 'signature_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs('signatures', $filename, 'public');

            $data['signature_image'] = $path; // signatures/filename.png
        }

        /* ================= UPDATE USER ================= */
        $user->update($data);

        /* ================= RESPONSE ================= */
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
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

        // Sirf PUBLIC tabs
        $tabs = ProfileTab::where('user_id', $user->id)
                    ->where('profile_type', 'PU')
                    ->get();

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
