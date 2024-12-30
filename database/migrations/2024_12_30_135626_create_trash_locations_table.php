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
        Schema::create('trash_locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude');
            $table->decimal('Longitude');
            $table->foreignId('trash_category_id')->constrained('trash_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_locations');
    }
};
