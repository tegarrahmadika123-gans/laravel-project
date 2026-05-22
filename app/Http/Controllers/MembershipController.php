<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function index()
    {
        return view('membership.index');
    }

    public function store(Request $request)
    {
        $request->validate([

            'paket' => 'required',

            'harga' => 'required|integer',

            'durasi_hari' => 'required|integer',

            'payment_method' => 'required',


        ]);


        // simpan membership
        Membership::create([

            'user_id' => Auth::id(),

            'paket' => $request->paket,

            'harga' => $request->harga,

            'durasi_hari' => $request->durasi_hari,

            'payment_method' => $request->payment_method,

            'payment_status' => 'pending'

        ]);

        return back()->with(
            'success',
            'Membership berhasil diajukan, tunggu verifikasi admin.'
        );
    }
}