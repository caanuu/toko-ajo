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
        Schema::create('stock_adjustment_details', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel stock_adjustments
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->onDelete('cascade');

            // Relasi ke tabel products
            $table->foreignId('product_id')->constrained('products');

            $table->decimal('qty_change', 15, 2); // Jumlah selisih (bisa minus atau plus)
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_details');
    }
};
