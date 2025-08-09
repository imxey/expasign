<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('team_name');
            $table->string('category')->nullable();
            $table->integer('nominal')->nullable();
            $table->string('receipt_path')->nullable();
            $table->boolean('isExpa')->default(true);
            $table->boolean('isEdu');
            $table->boolean('isSubmit')->default(false);
            $table->integer('code')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
