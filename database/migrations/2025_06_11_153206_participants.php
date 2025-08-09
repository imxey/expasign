<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id');
            $table->string('name');
            $table->string('nim')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('school')->nullable();
            $table->string('igLink');
            $table->string('followExpa');
            $table->string('followEdu');
            $table->string('followMp');
            $table->string('repostSg');
            $table->enum('role', ['leader', 'member'])->default('member');
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
