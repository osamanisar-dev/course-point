<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if ($request->has('success')) {
            session()->flash('success', 'Payment was successful!');
        }
        return view('dashboard');
    }

    public function downloadBible()
    {
        $filePath = public_path('PDF/SMC Bible.pdf');

        if (file_exists($filePath)) {
            if (Auth::user()->buy_bible === 1) {
                return response()->download($filePath, 'SmartMoneyConceptBible.pdf');
            } else {
                return redirect()->back()->with('error', 'Unauthorised access.');
            }
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}
