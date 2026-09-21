<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@smartotto.test'],
            [
                'name'     => 'Admin',
                'phone'    => '',
                'password' => Hash::make('Admin@2026'),
                'role'     => 'admin',
                'address'  => '',
            ]
        );

        // ── Inspektor 1 ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'inspektor1@smartotto.test'],
            [
                'name'     => 'Inspektor 1',
                'phone'    => '',
                'password' => Hash::make('Inspektor@2026'),
                'role'     => 'inspector',
                'address'  => '',
            ]
        );

        // ── Inspektor 2 ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'inspektor2@smartotto.test'],
            [
                'name'     => 'Inspektor 2',
                'phone'    => '',
                'password' => Hash::make('Inspektor@2026'),
                'role'     => 'inspector',
                'address'  => '',
            ]
        );

        // Customer: tidak ada data dummy.
        // Customer daftar sendiri melalui halaman /register.
    }
}
