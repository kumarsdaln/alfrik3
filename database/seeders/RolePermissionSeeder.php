<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Interviews
            'interviews.view',
            'interviews.create',
            'interviews.update',
            'interviews.delete',
            'interviews.publish',

            // Magazines
            'magazines.view',
            'magazines.create',
            'magazines.update',
            'magazines.delete',
            'magazines.publish',

            // Research
            'research.view',
            'research.create',
            'research.update',
            'research.delete',
            'research.publish',

            // Reports
            'reports.view',
            'reports.create',
            'reports.update',
            'reports.delete',
            'reports.publish',

            // Surveys
            'surveys.view',
            'surveys.create',
            'surveys.update',
            'surveys.delete',
            'surveys.publish',

            // News
            'news.view',
            'news.create',
            'news.update',
            'news.delete',
            'news.publish',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Settings
            'settings.view',
            'settings.update',

            // Moderation
            'moderation.view',
            'moderation.approve',
            'moderation.reject',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission],
                [
                    'name' => str($permission)
                        ->replace('.', ' ')
                        ->replace('_', ' ')
                        ->title()
                        ->toString(),

                    'description' => null,
                ],
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Full unrestricted access to the platform.',
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrative access to the platform.',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Manages and edits published content.',
            ],
            [
                'name' => 'Researcher',
                'slug' => 'researcher',
                'description' => 'Creates and manages research content.',
            ],
            [
                'name' => 'Author',
                'slug' => 'author',
                'description' => 'Creates and manages own content.',
            ],
            [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Reviews and moderates submitted content.',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular platform user.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                ],
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role Permissions
        |--------------------------------------------------------------------------
        */

        $this->assignPermissions();
    }

    private function assignPermissions(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::where('slug', 'super-admin')->firstOrFail();

        $superAdmin->permissions()->sync(
            Permission::pluck('id')->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        */

        $admin = Role::where('slug', 'admin')->firstOrFail();

        $adminPermissions = [
            'interviews.view',
            'interviews.create',
            'interviews.update',
            'interviews.delete',
            'interviews.publish',

            'magazines.view',
            'magazines.create',
            'magazines.update',
            'magazines.delete',
            'magazines.publish',

            'research.view',
            'research.create',
            'research.update',
            'research.delete',
            'research.publish',

            'reports.view',
            'reports.create',
            'reports.update',
            'reports.delete',
            'reports.publish',

            'surveys.view',
            'surveys.create',
            'surveys.update',
            'surveys.delete',
            'surveys.publish',

            'news.view',
            'news.create',
            'news.update',
            'news.delete',
            'news.publish',

            'users.view',
            'users.create',
            'users.update',

            'roles.view',
            'roles.create',
            'roles.update',

            'settings.view',
            'settings.update',

            'moderation.view',
            'moderation.approve',
            'moderation.reject',
        ];

        $admin->permissions()->sync(
            Permission::whereIn('slug', $adminPermissions)
                ->pluck('id')
                ->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Editor
        |--------------------------------------------------------------------------
        */

        $editor = Role::where('slug', 'editor')->firstOrFail();

        $editorPermissions = [
            'interviews.view',
            'interviews.create',
            'interviews.update',
            'interviews.publish',

            'magazines.view',
            'magazines.create',
            'magazines.update',
            'magazines.publish',

            'news.view',
            'news.create',
            'news.update',
            'news.publish',

            'reports.view',
            'reports.create',
            'reports.update',

            'moderation.view',
        ];

        $editor->permissions()->sync(
            Permission::whereIn('slug', $editorPermissions)
                ->pluck('id')
                ->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Researcher
        |--------------------------------------------------------------------------
        */

        $researcher = Role::where('slug', 'researcher')->firstOrFail();

        $researcherPermissions = [
            'research.view',
            'research.create',
            'research.update',
            'research.publish',

            'reports.view',
            'reports.create',
            'reports.update',

            'surveys.view',
            'surveys.create',
            'surveys.update',
        ];

        $researcher->permissions()->sync(
            Permission::whereIn('slug', $researcherPermissions)
                ->pluck('id')
                ->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Author
        |--------------------------------------------------------------------------
        */

        $author = Role::where('slug', 'author')->firstOrFail();

        $authorPermissions = [
            'interviews.view',
            'interviews.create',
            'interviews.update',

            'magazines.view',
            'magazines.create',
            'magazines.update',

            'news.view',
            'news.create',
            'news.update',

            'research.view',
            'research.create',
            'research.update',
        ];

        $author->permissions()->sync(
            Permission::whereIn('slug', $authorPermissions)
                ->pluck('id')
                ->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Moderator
        |--------------------------------------------------------------------------
        */

        $moderator = Role::where('slug', 'moderator')->firstOrFail();

        $moderatorPermissions = [
            'interviews.view',
            'magazines.view',
            'research.view',
            'reports.view',
            'surveys.view',
            'news.view',

            'moderation.view',
            'moderation.approve',
            'moderation.reject',
        ];

        $moderator->permissions()->sync(
            Permission::whereIn('slug', $moderatorPermissions)
                ->pluck('id')
                ->all()
        );

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $user = Role::where('slug', 'user')->firstOrFail();

        $userPermissions = [
            'interviews.view',
            'magazines.view',
            'research.view',
            'reports.view',
            'surveys.view',
            'news.view',
        ];

        $user->permissions()->sync(
            Permission::whereIn('slug', $userPermissions)
                ->pluck('id')
                ->all()
        );
    }
}