<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $users = User::where('is_email_verified',1)->orderBy('id', 'desc')->get();
            $userTypes = User::select('user_type')
                            ->distinct()
                            ->where('is_email_verified',1)
                            ->whereNotNull('user_type')
                            ->pluck('user_type');
            
            return view('admin.users.index', compact('users', 'userTypes'));
        } catch (\Exception $e) {
            \Log::error('Error fetching users list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading users: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $userTypes = User::select('user_type')
                            ->distinct()
                            ->whereNotNull('user_type')
                            ->pluck('user_type');
                            
            return view('admin.users.create', compact('userTypes'));
        } catch (\Exception $e) {
            \Log::error('Error loading user creation form: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error loading user creation form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'] ?? null,
            ]);

            // Set custom product limit if provided
            if (isset($validated['product_limit'])) {
                Setting::updateOrCreate(
                    ['key' => 'max_products_user_' . $user->id],
                    ['value' => $validated['product_limit']]
                );
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function edit($slug)
    {
        try {
            // Make sure there's no debug statement here - remove dd($user) if present
            $user = User::where('slug', $slug)->firstOrFail();
            
            // Ensure user has a slug
            if (empty($user->slug)) {
                // Generate the slug if missing
                $user->slug = $user->generateUniqueSlug($user->name ?: 'user-'.$user->id);
                // Specifically update only the slug field
                \DB::table('users')->where('id', $user->id)->update(['slug' => $user->slug]);
            }
            
            return view('admin.users.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, $slug)
    {
        try {
            $user = User::where('slug', $slug)->firstOrFail();
            $userId = $user->id;
            
            \Log::info('Update request for user ID: ' . $userId, [
                'mobile_number' => $request->mobile_number,
                'dob' => $request->dob
            ]);
            
            $validated = $request->validated();

            $updateData = [
                'mobile_number' => $validated['mobile_number'],
                'dob' => $validated['dob'],
                'country_code' => $request->input('country_code'),
                'country_iso' => $request->input('country_iso'),
                'updated_at' => now()
            ];
            
            if ($request->has('first_name')) {
                $updateData['first_name'] = $request->input('first_name');
            }
            
            if ($request->has('last_name')) {
                $updateData['last_name'] = $request->input('last_name');
            }
            
            if ($request->has('name') && !$request->has('first_name') && !$request->has('last_name')) {
                $nameParts = explode(' ', $request->input('name'), 2);
                $updateData['first_name'] = $nameParts[0];
                $updateData['last_name'] = isset($nameParts[1]) ? $nameParts[1] : '';
            }
            
            $updated = User::where('id', $userId)->update($updateData);
                
            \Log::info('User update result: ' . ($updated ? 'success' : 'no changes'));
            
            $updatedUser = User::where('id', $userId)->first();
            
            if ($updated) {
                return redirect()->back()->with('success', 'User updated successfully.');
            } else {
                return redirect()->back()->with('info', 'No changes were made.');
            }
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * Update user status (approve/reject)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, User $user)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,rejected'
            ]);
            
            $user->status = $request->status;
            $user->save();
            
            return redirect()->route('admin.users.index')
                ->with('success', "User status updated to " . ucfirst($request->status));
        } catch (\Exception $e) {
            \Log::error('Error updating user status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating user status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Block/unblock a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(User $user)
    {
        try {
            // Toggle the blocked status
            $user->is_blocked = !$user->is_blocked;
            $user->blocked_at = $user->is_blocked ? now() : null;
            $user->blocked_reason = $user->is_blocked ? request('reason', 'Blocked by administrator') : null;
            $user->save();
            
            $status = $user->is_blocked ? 'blocked' : 'unblocked';
            
            return redirect()->back()->with('success', "User has been {$status} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating user block status: ' . $e->getMessage());
        }
    }
}
