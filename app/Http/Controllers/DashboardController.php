<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Visitor;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Membership;
use App\Models\EbookRead;
use App\Models\EbookDownload;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $totalBooks = Cache::remember(
            'total_books',
            60,
            fn () => Book::count()
        );

        $totalEbooks = Cache::remember(
            'total_ebooks',
            60,
            fn () => Ebook::count()
        );

        $totalUsers = Cache::remember(
            'total_users',
            60,
            fn () => User::count()
        );

        $borrowedBooks = Cache::remember(
            'borrowed_books',
            60,
            fn () => Borrowing::where('status', 'dipinjam')->count()
        );

        $criticalStock = Book::where('stok', '<=', 3)
            ->count();

        $totalDenda = Borrowing::selectRaw(
    'SUM(COALESCE(denda,0) + COALESCE(denda_tambahan,0)) as total'
)->value('total') ?? 0;

        $membershipIncome = Membership::where('payment_status', 'paid')
    ->sum('harga');

    $totalMembers = Membership::where('payment_status', 'paid')
    ->where('expired_at', '>', now())
    ->count();
    
        $topReadEbooks = EbookRead::select(
                'ebook_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('ebook_id')
            ->orderByDesc('total')
            ->with('ebook')
            ->take(5)
            ->get();

        $topDownloadEbooks = EbookDownload::select(
                'ebook_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('ebook_id')
            ->orderByDesc('total')
            ->with('ebook')
            ->take(5)
            ->get();

        $topBorrowedBooks = Borrowing::select(
                'book_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->with('book')
            ->take(5)
            ->get();

        $visitorCount = Visitor::count();

        $todayVisitors = Visitor::whereDate('created_at', now()->timezone('Asia/Jakarta'))->count();

        // 1. Ambil data asli dari DB
$rawData = Borrowing::select(
    DB::raw('DATE(created_at) as date'),
    DB::raw('count(*) as total')
)
->where('created_at', '>=', now()->subDays(6)->startOfDay())
->groupBy('date')
->get()
->pluck('total', 'date'); // Format: ['2026-05-06' => 5]

// 2. Buat range 7 hari terakhir (agar hari yang kosong tetap muncul sebagai 0)
$borrowChart = collect();
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i)->format('Y-m-d');
    $borrowChart->push((object)[
        'date' => $date,
        'total' => $rawData->get($date, 0) // Jika tidak ada data, kasih 0
    ]);
}

        return view('dashboard', compact(

            'totalBooks',
            'totalEbooks',
            'totalUsers',
            'borrowedBooks',
            'criticalStock',
            'totalDenda',
            'membershipIncome',
            'topReadEbooks',
            'topDownloadEbooks',
            'topBorrowedBooks',
            'visitorCount',
            'todayVisitors',
            'borrowChart',
            'totalMembers'

        ));

        }
}
