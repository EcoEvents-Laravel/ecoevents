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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('banner_url')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('country');
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->integer('max_participants')->nullable();
            $table->string('status')->default('draft');  // e.g., draft, published, cancelled
            $table->foreignId('event_type_id')->constrained('event_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
