<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'portfolio:admin {--email= : Administrator email} {--name= : Administrator name}';

    protected $description = 'Create a portfolio administrator securely (password entered privately)';

    public function handle(): int
    {
        $email = $this->option('email') ?: $this->ask('Administrator email');
        $name = $this->option('name') ?: $this->ask('Administrator name', 'مدير الموقع');
        $password = $this->secret('Password (at least 12 characters)');
        $confirmation = $this->secret('Confirm password');
        $validator = Validator::make([
            'email' => $email, 'name' => $name, 'password' => $password, 'password_confirmation' => $confirmation,
        ], [
            'email' => ['required', 'email', 'max:254', 'unique:users,email'],
            'name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }
        User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        $this->info('Administrator created. Sign in at /login.');

        return self::SUCCESS;
    }
}
