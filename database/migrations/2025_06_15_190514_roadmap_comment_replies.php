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
        Schema::create('roadmap_comment_replies', function(Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('roadmap_comments');
            $table->foreignId('user_id')->constrained('users');
            $table->text('content');
            $table->boolean('edited')->default(0);
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
