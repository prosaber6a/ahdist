<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserSettingController extends Controller
{


    public function check_admin()
    {
        // if current not admin then redirect to dashboard
        if (Auth::user()->user_type != 1) {
            return redirect()->route('dashboard');
        }
    }

    public function index()
    {
        $this->check_admin();
        $users = User::all();
        return view('user.index', ['users' => $users]);

    }


    //create new user
    public function user_store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'user_type' => ['required', 'integer'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type
        ]);

        return redirect()->route('users_index')->with('success', 'Successfully user added');
    }

    public function user_edit(User $user)
    {
        $this->check_admin();
        $users = User::all();
        return view('user.index', ['users' => $users, 'edit_user' => $user]);
    }


    public function user_update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', Rules\Password::defaults()],
            'user_type' => ['required', 'integer'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = $request->password;
        }
        $user->user_type = $request->user_type;
        $user->save();

        return redirect()->route('users_index')->with('success', 'Successfully user updated');

    }


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

    /*
     * Edit user
     */


    public function change_current_user_password()
    {
        return view('user.change_password');
    }

    public function update_current_user_password(Request $request)
    {
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
