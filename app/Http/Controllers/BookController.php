<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->where('kategori', $request->category);
        }

        $books = $query->paginate()->withQueryString();

        return view('books.index', compact('books'));
    }

    // ✅ INI YANG KURANG
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'cover_url' => $coverPath
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'judul' => 'required',
            'stok' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $book = Book::findOrFail($id);

        if ($request->hasFile('cover_image')) {

            if ($book->cover_url && Storage::disk('public')->exists($book->cover_url)) {
                Storage::disk('public')->delete($book->cover_url);
            }

            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $book->cover_url = $coverPath;
        }

        $book->update([
            'judul' => $request->judul,
            'stok' => $request->stok,
        ]);

        return redirect('/books')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(int $id)
    {
        $book = Book::findOrFail($id);

        if ($book->borrowings()->where('status', 'dipinjam')->exists()) {
            return back()->with('error', 'Buku sedang dipinjam, tidak bisa dihapus!');
        }

        if ($book->cover_url && Storage::disk('public')->exists($book->cover_url)) {
            Storage::disk('public')->delete($book->cover_url);
        }

        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
}