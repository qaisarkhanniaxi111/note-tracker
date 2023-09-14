<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function editProfile()
    {
        $user = auth()->user();

        if (! $user) {
            abort(404, 'Unable to locate the user, please go back and refreshe the webpage and try again, if problem still persists contact with administrator');
        }

        return view('admin.profile.edit', compact('user'));
    }

    public function updateProfile(UpdateRequest $request, $userId)
    {
        $request->validated();
        $oldPassword = $request->old_password;

        $user = User::find($userId);

        if ($request->password == null) {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

        }
        else {

            if (! Hash::check($oldPassword, $user->password)) {
                return back()->withErrors('Old password does not match');
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        }

        $request->session()->flash('alert-success', 'Profile Updated Successfully');

        return to_route('admin.profile.edit');

    }
}
