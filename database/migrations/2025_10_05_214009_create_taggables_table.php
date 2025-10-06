// path: database/migrations/YYYY_MM_DD_HHMMSS_create_taggables_table.php
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
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('taggable'); // Creates taggable_id (unsignedBigInteger) and taggable_type (string)

            // To prevent duplicate tags on the same model
            $table->primary(['tag_id', 'taggable_id', 'taggable_type'], 'taggables_primary_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};