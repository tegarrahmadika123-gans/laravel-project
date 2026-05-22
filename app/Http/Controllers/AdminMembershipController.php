<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Carbon\Carbon;

class AdminMembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::with('user')
            ->latest()
            ->get();

        return view(
            'admin.memberships.index',
            compact('memberships')
        );
    }

    public function approve(int $id)
{
    $membership = Membership::findOrFail($id);

    // jika sudah pernah approve
    if($membership->payment_status == 'paid'){
        return back()->with('error', 'Membership sudah diapprove');
    }

    $user = $membership->user;

    // cek membership aktif user
    $activeMembership = Membership::where('user_id', $user->id)
        ->where('payment_status', 'paid')
        ->where('expired_at', '>', now())
        ->latest()
        ->first();

    // jika masih ada membership aktif
    if($activeMembership){

        $startDate = $activeMembership->expired_at;

    } else {

        $startDate = now();

    }

    // hitung expired baru
    $expiredDate = \Carbon\Carbon::parse($startDate)
        ->addDays($membership->durasi_hari);

    // update membership
    $membership->update([
        'payment_status' => 'paid',
        'started_at' => $startDate,
        'expired_at' => $expiredDate
    ]);

    return back()->with(
        'success',
        'Membership berhasil diapprove'
    );
}

    public function reject(int $id)
{
    $membership = Membership::findOrFail($id);

    $membership->update([
        'payment_status' => 'rejected'
    ]);

    return back()->with(
        'success',
        'Membership ditolak'
    );
}
}