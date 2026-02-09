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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Primary account owner
            $table->string('email');
            $table->string('name');
            $table->enum('role', ['admin', 'member', 'viewer'])->default('member');
            $table->string('invitation_token')->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('invitation_accepted_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent duplicate team members
            $table->unique(['user_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
