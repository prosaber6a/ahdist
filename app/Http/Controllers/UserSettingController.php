<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingController extends Controller
{
    public function current_user_setting()
    {
        return view('user.setting');
    }

    public function update_current_user_setting(Request $request)
    {
        //validate requested data
        $request->validate([
            'name' => 'required|string|min:5|max:60',
            'email' => 'required|email'
        ]);

        // find current user
        $user = User::find(auth()->id());

        // set date
        $user->name = $request->name;
        $user->email = $request->email;
        // update in DB
        $user->save();

        return redirect()->route('current_user_setting')->with('success', 'Successfully account info updated');

    }

    public function change_current_user_password () {
        return view('user.change_password');
    }

    public function update_current_user_password(Request $request) {
        $request->validate([
            'current_password' => 'required|string|min:8|max:18',
            'password' => 'required|string|min:8|max:18|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->route('change_current_user_password')->withError('Current Password does not match');
        }

        $user = User::find(Auth::id());

        try {
            $user->password = Hash::make($request->password);
            $user->save();
        } catch (\Exception $exception) {
            return redirect()->route('change_current_user_password')->withError("An Error occurred while update password<br/>" . $exception->getMessage());
        }

        return redirect()->route('change_current_user_password')->with('success', 'Successfully Password Updated');
    }
}
