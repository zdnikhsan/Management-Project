<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default('active')->after('description'); // active, archived
            $table->date('due_date')->nullable()->after('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('available')->after('email'); // available, busy
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['status', 'due_date']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
