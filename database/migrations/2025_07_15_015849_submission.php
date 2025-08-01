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
        Schema::create('submission', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('registrant_id'); 
            $table->string('file'); 
            $table->timestamps();
            $table->foreign('registrant_id')->references('id')->on('registrants')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission');
        
    }
};
