<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if ($this->usingSqlite()) {
            $this->rebuildUsersTableForSqlite();

            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 32)->unique()->after('id');
            $table->string('username')->nullable()->unique()->after('mobile');
            $table->softDeletes();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
        });

        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->usingSqlite()) {
            $this->rebuildUsersTableForSqliteDown();

            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mobile',
                'username',
                'deleted_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
        });

        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
    }

    private function usingSqlite(): bool
    {
        return Schema::getConnection()->getDriverName() === 'sqlite';
    }

    private function rebuildUsersTableForSqlite(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');

        Schema::create('users_temp', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 32)->unique();
            $table->string('username')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('users_temp')->insert([
                'id' => $user->id,
                'mobile' => 'migration-temp-' . $user->id,
                'username' => null,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => null,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_temp', 'users');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    private function rebuildUsersTableForSqliteDown(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');

        Schema::create('users_temp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('users_temp')->insert([
                'id' => $user->id,
                'name' => $user->name ?? '',
                'email' => $user->email ?? 'user-' . $user->id . '@example.com',
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password ?? '',
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_temp', 'users');

        DB::statement('PRAGMA foreign_keys=ON');
    }
};
