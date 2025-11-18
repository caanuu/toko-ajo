<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // Kode Adjustment
            $table->dateTime('date');             // Tanggal Adjustment
            $table->string('reason', 150);        // Alasan (Rusak, Hilang, Salah Input)
            $table->text('notes')->nullable();    // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
