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
            $table->ulid('ulid')->unique();

            $table->renameColumn('name', 'username');
            $table->string('username', 30)->unique()->change();

            $table->dateTime('deleted_at', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ulid');
            $table->dropColumn('deleted_at');

            $table->dropUnique(['username']);
            $table->string('username')->change();
            $table->renameColumn('username', 'name');
        });
    }
};
