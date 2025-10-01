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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pf_iduser');
            $table->foreign('pf_iduser')->references('id')->on('users')->onDelete('cascade');
            $table->string('pf_company')->nullable();
            $table->string('pf_wa', 15)->nullable();
            $table->string('pf_telegram', 15)->nullable();
            $table->text('pf_address')->nullable();
            $table->string('pf_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
