<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comic_id')->constrained('comics')->cascadeOnDelete();
            $table->decimal('chapter_number', 8, 2);
            $table->string('title')->nullable();
            $table->string('slug');
            $table->unsignedInteger('price_coins')->default(0);
            $table->boolean('is_vip')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();

            $table->unique(['comic_id', 'chapter_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
