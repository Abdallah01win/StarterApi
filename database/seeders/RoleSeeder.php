<?php

namespace Database\Seeders;

use App\Enums\RoleNames;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleNames::getValues() as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
