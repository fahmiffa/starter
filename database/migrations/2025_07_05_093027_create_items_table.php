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
            $table->string('kode')->nullable();
            $table->string('name');
            $table->decimal('price');
            $table->string('img')->nullable();
            $table->integer('pcs')->default(0);
            $table->Biginteger('app')->nullable();
            $table->Biginteger('cat')->nullable();
            $table->Biginteger('unit')->nullable();
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
