<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Books;
use App\Models\Members;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Books::create([
            'code' => 'JK-45',
            'title' => 'Harry Potter',
            'author' => 'J.K Rowling',
            'stock' => 1,
        ]);

        Books::create([
            'code' => 'SHR-1',
            'title' => 'A Study in Scarlet',
            'author' => 'Arthur Conan Doyle',
            'stock' => 1,
        ]);

        Books::create([
            'code' => 'TW-11',
            'title' => 'Twilight',
            'author' => 'Stephenie Meyer',
            'stock' => 1,
        ]);

        Books::create([
            'code' => 'HOB-83',
            'title' => 'The Hobbit, or There and Back Again',
            'author' => 'J.R.R. Tolkien',
            'stock' => 1,
        ]);

        Books::create([
            'code' => 'NRN-7',
            'title' => 'The Lion, the Witch and the Wardrobe',
            'author' => 'C.S. Lewis',
            'stock' => 1,
        ]);

        // Seed Members
        Members::create([
            'code' => 'M001',
            'name' => 'Angga',
        ]);

        Members::create([
            'code' => 'M002',
            'name' => 'Ferry',
        ]);

        Members::create([
            'code' => 'M003',
            'name' => 'Putri',
        ]);
    }
}
