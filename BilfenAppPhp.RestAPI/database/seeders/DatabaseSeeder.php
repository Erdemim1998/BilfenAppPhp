<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Role::factory()->create([
            'Name' => 'ADMIN'
        ]);

        Role::factory()->create([
            'Name' => 'HR'
        ]);

        Role::factory()->create([
            'Name' => 'USER'
        ]);

        User::factory()->create([
            'FirstName' => 'Güray',
            'LastName' => 'Kaymaz',
            'UserName' => 'Gurayim',
            'Email' => 'guraykaymaz@bilfen.com',
            'Password' => 'Güray1970',
            'PasswordHash' => bcrypt('Güray1970'),
            'RoleId' => 1
        ]);

        User::factory()->create([
            'FirstName' => 'Celal',
            'LastName' => 'Özlal',
            'UserName' => 'Celalim',
            'Email' => 'celalozlal@bilfen.com',
            'Password' => 'Celal1980',
            'PasswordHash' => bcrypt('Celal1980'),
            'RoleId' => 2
        ]);

        User::factory()->create([
            'FirstName' => 'Muhammed Erdem',
            'LastName' => 'Anaçoğlu',
            'UserName' => 'Erdemim',
            'Email' => 'erdemanacoglu90@gmail.com',
            'Password' => 'Erdem1998',
            'PasswordHash' => bcrypt('Erdem1998'),
            'RoleId' => 3
        ]);

        User::factory()->create([
            'FirstName' => 'Burcu',
            'LastName' => 'Yılmaz',
            'UserName' => 'BurcuY',
            'Email' => 'burcuyilmaz@gmail.com',
            'Password' => 'Burcu1998',
            'PasswordHash' => bcrypt('Burcu1998'),
            'RoleId' => 3
        ]);
    }
}
