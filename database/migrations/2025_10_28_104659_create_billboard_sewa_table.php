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
        Schema::create('billboard_sewa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('billboard_id')
                ->constrained('billboards')
                ->onDelete('cascade'); 

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); 
            
            $table->tinyInteger('periode'); 
            $table->date('tgl_awal'); 
            $table->date('tgl_akhir'); 

            $table->string('admin_buat', 100);
            $table->string('admin_ubah', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billboard_sewa');
    }
};
