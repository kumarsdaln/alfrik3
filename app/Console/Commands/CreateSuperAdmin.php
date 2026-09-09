<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

#[Signature('app:create-super-admin')]
#[Description('Command description')]
class CreateSuperAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Creating super admin user...');
        $this->components->warn('Please make sure to enter a valid email and a strong password.');
        $this->newLine();


        $name = $this->askForName();
        $email = $this->askForEmail();
        $password = $this->askForPassword();

        $role = Role::where('slug', 'super-admin')->first();
        if(!$role) {
            $this->components->error('Super admin role not found. Please create the role first.');
            return self::FAILURE;
        }

        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return $this->handleExistingUser($existingUser, $role);
        }

        if(!$this->confirm("Create Super admin account for {$email}", true)){
            $this->components->warn("Operation Cancelled");
            return self::SUCCESS;
        }

        try {
            DB::transection(function() use ($name, $email, $password, $role):void {
                $user = User::query()->create([
                    'name'=>$name,
                    'email'=>$email,
                    'password'=>Hash::make($password)
                ]);

                $user->roles()->syncWithoutDetaching([$role->id]);
            });
            

        } catch(Throwable $e) {
            report($e);

            $this->components->error("Failed to create super admin user.");

            return self::FAILURE;

        }

        $this->newLine();
        $this->components->success("Super admin {$email} was created successfully.");
    }


    private function askForName(): string
    {
        return $this->ask('Enter the name of the super admin user');
    }

    private function askForEmail(): string
    {
        while(true){
            $email = $this->ask('Enter the email of the super admin user');
            $email =  strtolower(trim($email));
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return $email;
            }

            $this->components->error("Please enter a valid email!");
        }
    }

    private function askForPassword(): string
    {
        while(true){
            $password = $this->secret('Enter the password of the super admin user');

            if(strlen($password)<8){
                $this->components->error("Password must be atleast 8 characters!");
                continue;
            }

            return $password;
        }
    }

    private function handleExistingUser(User $user, Role $role): int
    {
        if($user->roles()->whereKey($role->id)->exists()) {
            $this->components->warn("The user {$user->name} already has the super admin role.");
            return self::SUCCESS;
        } 

        $this->components->warn("The user {$user->name} already exists but does not have the super admin role.");
        if(!$this->confirm('Do you want to assign the super admin role to this user?', false)) {
            $this->components->info('Operation cancelled.');
            return self::SUCCESS;
        }

        $user->roles()->syncWithoutDetaching([$role->id]);
        $this->components->success("The super admin role has been assigned to the user {$user->name}.");
        return self::SUCCESS;
    }
}
