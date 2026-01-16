<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfileTab;
use Illuminate\Support\Facades\Hash;

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
            'profile_image' => 'nullable|image|max:2048',
            'signature_image' => 'nullable|image|max:2048',
            'bio' => 'nullable',
        ]);

        /* ================= PASSWORD ================= */
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        /* ================= PROFILE IMAGE ================= */
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        /* ================= SIGNATURE IMAGE ================= */
        if ($request->hasFile('signature_image')) {
            if ($user->signature_image) {
                Storage::disk('public')->delete($user->signature_image);
            }
            $data['signature_image'] = $request->file('signature_image')->store('signatures', 'public');
        }

        $user->update($data);

        /* ================= AJAX RESPONSE ================= */
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
}
