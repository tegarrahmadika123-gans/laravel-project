<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Mendapatkan IP Address visitor
        $ip = $request->ip();
        $today = now()->toDateString();

        // Cek apakah IP ini sudah dicatat hari ini (mencegah spam refresh)
        $alreadyVisited = Visitor::where('ip_address', $ip)
                                ->whereDate('created_at', $today)
                                ->exists();

        if (!$alreadyVisited) {
            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}