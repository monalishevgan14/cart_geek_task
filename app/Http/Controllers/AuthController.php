<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   
    public function showRegister(){
         return view('auth.register');
    }

    public function register(Request $request){
        
        // dd("ok");
        //  dd("Register method hit");
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password'=>'required|string|min:5|confirmed'
        ]);

        $password = Hash::make($request->password);
        
        // store value
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$password

        ]);

        // auth()->login('user');
        return redirect()->route('login')
                         ->with('message','Register Successfully');
    }

    public function showLogin1()
    {
        if (Auth::check()) {
            return redirect()->route('products.index');
        }

        return view('auth.login');
    }

    public function login1(Request $request)
    {
        // 1️⃣ Validate
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2️⃣ Attempt Login
        // Auth::attempt();
        // Auth::user();
        // Auth::check();
        // Auth::logout();
        if (Auth::attempt($credentials)) {

            // regenerate session (security)
            $request->session()->regenerate();

           return redirect()->route('products.index')
                 ->with('success', 'Login Successfully');
        }

        // 3️⃣ If Login Fails
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function showLogin()
    {
        if (Auth::check()) {

            if (Auth::user()->role === 'admin') {
                return redirect()->route('adminProducts.index');
            }

            return redirect()->route('products.index');
        }

        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('adminProducts.index')
                    ->with('success','Admin Login Successfully');
            }

            return redirect()->route('products.index')
                ->with('success','User Login Successfully');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }


}
