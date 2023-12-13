<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->text('word');
        });

        if(DB::select("SELECT COUNT(*) FROM pg_extension WHERE extname = 'pg_trgm';")[0]->count === 0){
            DB::statement("create extension pg_trgm;");
        }

        DB::statement("CREATE INDEX words_idx ON words USING GIN (word gin_trgm_ops);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
        //DB::statement("drop extension pg_trgm;");
    }
};
