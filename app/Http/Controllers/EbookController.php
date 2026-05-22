<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;
use App\Models\EbookRead;
use App\Models\EbookDownload;



class EbookController extends Controller
{
public function index(Request $request)
{
    $query = Ebook::query();

    // SEARCH
    if ($request->search) {

        $query->where(function ($q) use ($request) {

            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('penulis', 'like', '%' . $request->search . '%');

        });

    }

    $ebooks = $query->latest()->get();

    return view('ebooks.index', compact('ebooks'));
}
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'deskripsi' => 'nullable',
            'cover' => 'required|image',
            'pdf_file' => 'required|mimes:pdf'
        ]);

        // Upload Cover
        $coverPath = $request->file('cover')->store('ebooks/covers', 'public');

        // Upload PDF
        $pdfPath = $request->file('pdf_file')->store('ebooks/pdfs', 'public');

        // Simpan Database
        Ebook::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'deskripsi' => $request->deskripsi,
            'cover' => $coverPath,
            'pdf_file' => $pdfPath,
        ]);

        return back()->with('success', 'E-Book berhasil ditambahkan!');
    }
    public function update(Request $request, int $id)
{
    $ebook = Ebook::findOrFail($id);

    $request->validate([
        'judul' => 'required',
        'penulis' => 'required',
        'deskripsi' => 'nullable',
    ]);

    // Update Cover jika ada
    if ($request->hasFile('cover')) {

        $coverPath = $request->file('cover')
            ->store('ebooks/covers', 'public');

        $ebook->cover = $coverPath;
    }

    // Update PDF jika ada
    if ($request->hasFile('pdf_file')) {

        $pdfPath = $request->file('pdf_file')
            ->store('ebooks/pdfs', 'public');

        $ebook->pdf_file = $pdfPath;
    }

    // Update data
    $ebook->judul = $request->judul;
    $ebook->penulis = $request->penulis;
    $ebook->deskripsi = $request->deskripsi;

    $ebook->save();

    return back()->with('success', 'E-Book berhasil diupdate!');
}
public function destroy(int $id)
{
    $ebook = Ebook::findOrFail($id);

    $ebook->delete();

    return back()->with('success', 'E-Book berhasil dihapus!');
}
public function read(Request $request, int $id)
{
    $membership = \App\Models\Membership::where('user_id', Auth::id())
        ->where('payment_status', 'paid')
        ->where('expired_at', '>', now())
        ->first();

    if (!$membership) {
        return redirect('/ebooks')
            ->with('error', 'E-Book hanya untuk member aktif');
    }

    $ebook = Ebook::findOrFail($id);

    $request->validate([
        'npm' => 'required',
        'email' => 'required|email'
    ]);

    EbookRead::create([
        'user_id' => Auth::id(),
        'ebook_id' => $ebook->id,
        'npm' => $request->npm,
        'email' => $request->email,
    ]);

    return view('ebooks.read', compact('ebook'));
}
public function download(Request $request, int $id)
{
    $membership = \App\Models\Membership::where('user_id', Auth::id())
    ->where('payment_status', 'paid')
    ->where('expired_at', '>', now())
        ->first();

    if(!$membership){

        return redirect('/ebooks')
            ->with('error', 'Download hanya untuk member aktif');

    }

    $ebook = Ebook::findOrFail($id);

    $request->validate([
        'npm' => 'required',
        'email' => 'required|email'
    ]);
EbookDownload::create([

    'user_id' => Auth::id(),

    'ebook_id' => $ebook->id,

    'npm' => $request->npm,

    'email' => $request->email,

]);
    $path = storage_path('app/public/' . $ebook->pdf_file);

    return response()->download($path);
}
}