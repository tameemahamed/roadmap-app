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
        //
        Schema::create('roadmaps', function (Blueprint $table){
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('creator_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamp('preview_available_date')->nullable();
            $table->timestamp('rollout_start_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
