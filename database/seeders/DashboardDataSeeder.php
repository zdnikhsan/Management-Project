<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\LoginHistory;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $leaderRole = Role::firstOrCreate(['name' => 'leader']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create Leader
        $leader = User::firstOrCreate(
            ['email' => 'leader@example.com'],
            [
                'name' => 'Project Leader',
                'password' => Hash::make('password'),
            ]
        );
        $leader->assignRole($leaderRole);

        // Create Staff
        $staff1 = User::firstOrCreate(
            ['email' => 'staff1@example.com'],
            [
                'name' => 'John Staff',
                'password' => Hash::make('password'),
                'status' => 'busy'
            ]
        );
        $staff1->assignRole($staffRole);

        $staff2 = User::firstOrCreate(
            ['email' => 'staff2@example.com'],
            [
                'name' => 'Jane Staff',
                'password' => Hash::make('password'),
                'status' => 'available'
            ]
        );
        $staff2->assignRole($staffRole);

        // Create Projects
        $project1 = Project::updateOrCreate(
            ['name' => 'Project Alpha'],
            [
                'description' => 'A high-priority initiative for Q2.',
                'leader_id' => $leader->id,
                'status' => 'active',
                'due_date' => now()->addWeeks(4)
            ]
        );
        $project1->members()->syncWithoutDetaching([$staff1->id, $staff2->id]);

        $project2 = Project::updateOrCreate(
            ['name' => 'Archived Project Omega'],
            [
                'description' => 'Completed last year.',
                'leader_id' => $leader->id,
                'status' => 'archived',
                'due_date' => now()->subMonths(2)
            ]
        );

        // Create Tasks
        Task::updateOrCreate(
            ['title' => 'Design Database Schema'],
            [
                'project_id' => $project1->id,
                'user_id' => $staff1->id,
                'description' => 'Create ERD and migration files.',
                'status' => 'done',
                'due_date' => now()->subDays(2)
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Implement Auth System'],
            [
                'project_id' => $project1->id,
                'user_id' => $staff1->id,
                'description' => 'Setup Laravel Breeze and Spatie.',
                'status' => 'doing',
                'due_date' => now()->addDays(1)
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Frontend Mockups'],
            [
                'project_id' => $project1->id,
                'user_id' => $staff2->id,
                'description' => 'Create Figma designs for the dashboard.',
                'status' => 'todo',
                'due_date' => now()->addDays(5)
            ]
        );

        // Activities
        Activity::create([
            'user_id' => $admin->id,
            'action' => 'Updated system settings',
            'description' => 'Admin updated global SMTP settings.'
        ]);

        Activity::create([
            'user_id' => $leader->id,
            'action' => 'Created Project Alpha',
            'description' => 'Leader initiated the Alpha project.'
        ]);

        Activity::create([
            'user_id' => $staff1->id,
            'action' => 'Completed a task',
            'description' => 'John completed Database Schema task.'
        ]);

        // Login History
        LoginHistory::create([
            'user_id' => $admin->id,
            'ip_address' => '192.168.1.1',
            'location' => 'Jakarta, Indonesia',
            'login_at' => now()->subHours(2)
        ]);

        LoginHistory::create([
            'user_id' => $leader->id,
            'ip_address' => '192.168.1.5',
            'location' => 'Bandung, Indonesia',
            'login_at' => now()->subMinutes(30)
        ]);
    }
}
