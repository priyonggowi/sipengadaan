<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin Pengadaan',
            'email' => 'admin@sipengadaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT / General Affairs',
            'phone' => '08111111111'
        ]);
        
        User::create([
            'name' => 'Direktur Utama',
            'email' => 'director@sipengadaan.com',
            'password' => Hash::make('password'),
            'role' => 'director',
            'department' => 'Direksi',
            'phone' => '08999999999'
        ]);

        User::create([
            'name' => 'Manager Operasional',
            'email' => 'manager@sipengadaan.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department' => 'Operasional',
            'phone' => '08222222222'
        ]);

        User::create([
            'name' => 'Staf Administrasi',
            'email' => 'staff@sipengadaan.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'department' => 'Administrasi',
            'phone' => '08333333333'
        ]);

        // Categories
        ItemCategory::insert([
            ['name' => 'Alat Tulis Kantor (ATK)', 'slug' => Str::slug('Alat Tulis Kantor (ATK)'), 'icon' => '✏️', 'color' => 'blue', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IT & Komputer', 'slug' => Str::slug('IT & Komputer'), 'icon' => '💻', 'color' => 'indigo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cetak & Publikasi', 'slug' => Str::slug('Cetak & Publikasi'), 'icon' => '🖨️', 'color' => 'orange', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Peralatan Kebersihan', 'slug' => Str::slug('Peralatan Kebersihan'), 'icon' => '🧹', 'color' => 'green', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Konsumsi / Pantry', 'slug' => Str::slug('Konsumsi / Pantry'), 'icon' => '☕', 'color' => 'yellow', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Suppliers
        Supplier::insert([
            ['name' => 'Toko Buku Gramedia', 'contact' => '021-111111', 'category' => 'ATK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bhinneka IT', 'contact' => '021-222222', 'category' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Percetakan Bintang', 'contact' => '021-333333', 'category' => 'Cetak', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
