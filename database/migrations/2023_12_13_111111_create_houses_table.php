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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price')->index();
            $table->integer('bedrooms')->index();
            $table->integer('bathrooms')->index();
            $table->integer('storeys')->index();
            $table->integer('garages')->index();
            $table->boolean('is_main')->default(0)->index();

            $table->fullText('name')->language('english');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
