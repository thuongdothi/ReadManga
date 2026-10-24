<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('alternative_names')->nullable();
            $table->string('author')->nullable();
            $table->string('artist')->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->decimal('rating_score', 3, 2)->default(5.00);
            $table->unsignedInteger('rating_count')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
