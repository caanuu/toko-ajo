<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Mengubah semua kolom terkait kuantitas/stok menjadi INTEGER.
     */
    public function up(): void
    {
        // 1. products.stock (Dulu decimal 15, 3)
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->change();
        });

        // 2. stock_movements (Dulu decimal 15, 2)
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->integer('qty')->change();
            $table->integer('before_qty')->change();
            $table->integer('after_qty')->change();
        });

        // 3. purchase_details (Dulu decimal 15, 2)
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->integer('qty')->change();
        });

        // 4. sale_details (Dulu decimal 15, 2)
        Schema::table('sale_details', function (Blueprint $table) {
            $table->integer('qty')->change();
        });

        // 5. stock_adjustment_details (Dulu decimal 15, 2)
        Schema::table('stock_adjustment_details', function (Blueprint $table) {
            $table->integer('qty_change')->change();
        });
    }

    /**
     * Reverse the migrations.
     * Mengembalikan ke tipe data desimal seperti sebelumnya.
     */
    public function down(): void
    {
        // 1. products.stock
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock', 15, 3)->default(0)->change();
        });

        // 2. stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
            $table->decimal('before_qty', 15, 2)->change();
            $table->decimal('after_qty', 15, 2)->change();
        });

        // 3. purchase_details
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
        });

        // 4. sale_details
        Schema::table('sale_details', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
        });

        // 5. stock_adjustment_details
        Schema::table('stock_adjustment_details', function (Blueprint $table) {
            $table->decimal('qty_change', 15, 2)->change();
        });
    }
};
