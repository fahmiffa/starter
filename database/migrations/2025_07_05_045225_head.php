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
        Schema::create('head', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->decimal('nominal')->default(0);
            $table->date('tanggal')->nullable();
            $table->Biginteger('user')->nullable();
            $table->Biginteger('app')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
