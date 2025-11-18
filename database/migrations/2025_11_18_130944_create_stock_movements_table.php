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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->enum('movement_type', ['IN', 'OUT', 'ADJ']);
            $table->decimal('qty', 15, 2);
            $table->decimal('before_qty', 15, 2);
            $table->decimal('after_qty', 15, 2);
            $table->decimal('unit_cost', 15, 2)->nullable();
            // Kolom ref_type dan ref_id berguna untuk melacak ini stok dari penjualan ID berapa
            $table->string('ref_type', 50)->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('moved_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
