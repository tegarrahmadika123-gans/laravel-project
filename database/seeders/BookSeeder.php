<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('books')->insert([
    [
        'judul' => 'Clean Code',
        'penulis' => 'Robert C. Martin',
        'kategori' => 'Pemrograman',
        'stok' => 10
    ],
    [
        'judul' => 'Database System Concepts',
        'penulis' => 'Silberschatz',
        'kategori' => 'Database',
        'stok' => 8
    ]
]);
    }
}
