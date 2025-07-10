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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->default(null);
            $table->string('name');
            $table->string('img')->default(null);
            $table->integer('pcs')->default(0);
            $table->Biginteger('user')->default(null);
            $table->Biginteger('cat')->default(null);
            $table->Biginteger('unit')->default(null);
            $table->Biginteger('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
