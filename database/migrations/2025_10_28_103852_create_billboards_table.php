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
        Schema::create('billboards', function (Blueprint $table) {
            $table->id();

            $table->string('judul', 100);
            $table->string('area', 50);
            $table->string('lokasi', 255);

            $table->boolean('status')->default(1);
            $table->boolean('aktif')->default(1);
            $table->string('keterangan', 255)->nullable();
            
            $table->string('jenis', 100);
            $table->tinyInteger('lebar');
            $table->tinyInteger('panjang');
            $table->tinyInteger('unit')->default(1);

            $table->double('latitude'); 
            $table->double('longitude');

            $table->string('gambar', 100) ->nullable();
            
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
        Schema::dropIfExists('billboards');
    }
};
