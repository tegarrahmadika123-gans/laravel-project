@extends('layouts.app')

@section('content')

<h3>Edit Buku</h3>

<form action="/books/{{ $book->id }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ $book->judul }}">
    </div>

    <div class="mb-3">
        <label>Penulis</label>
        <input type="text" name="penulis" class="form-control" value="{{ $book->penulis }}">
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <input type="text" name="kategori" class="form-control" value="{{ $book->kategori }}">
    </div>

    <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" value="{{ $book->stok }}">
    </div>

    <button class="btn btn-primary">Update</button>
</form>

@endsection