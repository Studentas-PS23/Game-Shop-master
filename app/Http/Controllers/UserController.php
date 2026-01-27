<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function profile()
    {
        $user = auth()->user();
        $cartProducts = $user->cartProducts; // <-- pridėta eilutė
        $purchasedProducts = $user->purchasedProducts()->with('game')->get();

        return view('users.profile', compact('user', 'cartProducts', 'purchasedProducts'));
    }

    public function downloadPdf()
    {
        $user = Auth::user();
        $purchasedProducts = $user->purchasedProducts;

        // $pdf = PDF::loadView('users.profile_pdf', compact('user', 'purchasedProducts'));
        //return $pdf->download('mano-produktai.pdf');
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $user->name = $request->name;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->route('profile')->with('success', 'Profilis atnaujintas!');
    }
    
    public function purchased()
    {
        $products = auth()->user()->purchasedProducts()->with('game')->get();
        return view('users.purchased', compact('products'));
    }
}