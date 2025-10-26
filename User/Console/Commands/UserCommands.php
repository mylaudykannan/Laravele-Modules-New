<?php

namespace App\Modules\User\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCommands extends Command
{
    protected $signature = 'user-module:commands';
    protected $description = 'User module commands';

    public function handle()
    {
        
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
        
        Role::create(['name' => 'Admin']);
        $user->assignRole('Admin');

        $this->info('User command run successfully');
    }
}
