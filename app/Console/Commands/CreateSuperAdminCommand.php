<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ngo:create-super-admin
                            {--name= : Name of the Super Admin}
                            {--email= : Email address}
                            {--phone= : Phone number}
                            {--password= : Password (optional, will prompt securely if omitted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactively create or promote a Super Admin for the NGO platform';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  TAMIL NADU NGO PLATFORM — SUPER ADMIN BOOTSTRAP  ');
        $this->info('====================================================');

        // Ensure Super Admin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        $name = $this->option('name') ?: $this->ask('Enter Full Name');
        while (empty(trim($name))) {
            $this->error('Name is required.');
            $name = $this->ask('Enter Full Name');
        }

        $email = $this->option('email') ?: $this->ask('Enter Email Address');
        while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please enter a valid email address.');
            $email = $this->ask('Enter Email Address');
        }

        $phone = $this->option('phone') ?: $this->ask('Enter Phone Number (optional)');

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->warn("A user with email '{$email}' already exists.");
            if (!$this->confirm('Do you want to promote this existing user to Super Admin and optionally update password?', true)) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }

            $password = $this->option('password') ?: $this->secret('Enter New Password (press Enter to keep current password)');
            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $this->error('Password must be at least 8 characters.');
                    return self::FAILURE;
                }
                $confirmPassword = $this->secret('Confirm New Password');
                if ($password !== $confirmPassword) {
                    $this->error('Passwords do not match.');
                    return self::FAILURE;
                }
                $existingUser->password = Hash::make($password);
            }

            $existingUser->name = $name ?: $existingUser->name;
            if ($phone) {
                $existingUser->phone = $phone;
            }
            $existingUser->status = 'active';
            $existingUser->email_verified_at = $existingUser->email_verified_at ?: now();
            $existingUser->save();

            $existingUser->assignRole($superAdminRole);

            AuditService::log(
                'Promoted user to Super Admin via CLI',
                'users',
                $existingUser->id,
                null,
                ['email' => $existingUser->email, 'roles' => ['Super Admin']]
            );

            $this->info("✓ User '{$existingUser->name}' ({$existingUser->email}) successfully promoted to Super Admin!");
            return self::SUCCESS;
        }

        // New User Creation
        $password = $this->option('password');
        if (empty($password)) {
            $password = $this->secret('Enter Password (minimum 8 characters)');
            while (empty($password) || strlen($password) < 8) {
                $this->error('Password must be at least 8 characters long.');
                $password = $this->secret('Enter Password (minimum 8 characters)');
            }

            $confirmPassword = $this->secret('Confirm Password');
            while ($password !== $confirmPassword) {
                $this->error('Passwords do not match.');
                $password = $this->secret('Enter Password (minimum 8 characters)');
                $confirmPassword = $this->secret('Confirm Password');
            }
        } elseif (strlen($password) < 8) {
            $this->error('Password option must be at least 8 characters long.');
            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone ?: null,
            'password' => Hash::make($password),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $user->assignRole($superAdminRole);

        AuditService::log(
            'Created Super Admin via CLI',
            'users',
            $user->id,
            null,
            ['email' => $user->email, 'roles' => ['Super Admin']]
        );

        $this->newLine();
        $this->info("✓ Super Admin '{$user->name}' ({$user->email}) created successfully!");
        $this->info("You can now log in at /login with these credentials.");
        $this->newLine();

        return self::SUCCESS;
    }
}
