<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('requisition_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'requisition_id']); // One review per requisition per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
