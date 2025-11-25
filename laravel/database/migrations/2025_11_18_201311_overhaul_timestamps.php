<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('email_verified_at', 6)->nullable()->change();
            $table->dateTime('created_at', 6)->change();
            $table->dateTime('updated_at', 6)->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dateTime('created_at', 6)->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dateTime('last_used_at', 6)->nullable()->change();
            $table->dateTime('expires_at', 6)->nullable()->change();
            $table->dateTime('created_at', 6)->change();
            $table->dateTime('updated_at', 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->timestamp('last_used_at')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->change();
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }
};
