<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();

            $table->string('slug', 50)->nullable()->unique();
            $table->string('display_name', 80)->nullable();
            $table->string('description', 1000)->nullable();
            $table->enum('type', ['channel', 'group', 'dm']);

            $table->datetime('created_at', 6);
            $table->datetime('updated_at', 6);
            $table->datetime('archived_at', 6)->nullable();
            $table->datetime('deleted_at', 6)->nullable();
        });

        DB::statement('
            ALTER TABLE rooms
            ADD CONSTRAINT rooms_public_slug_private_no_slug
            CHECK (
                (type = \'channel\' AND slug IS NOT NULL) OR
                (type <> \'channel\' AND slug IS NULL)
            )
        ');

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();

            $table->foreignId('room_id')->constrained('rooms');
            $table->foreignId('parent_id')->nullable()->constrained('messages');
            $table->foreignId('sender_id')->constrained('users');
            $table->string('body', 4096);

            $table->datetime('created_at', 6);
            $table->datetime('updated_at', 6);
            $table->datetime('deleted_at', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('rooms');
    }
};
