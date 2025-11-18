<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- WAJIB ADA agar tidak error

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kunci setting (misal: default_tax)
            $table->text('value')->nullable(); // Nilai setting (misal: 11)
            $table->timestamps();
        });

        // Insert Data Default agar tidak error saat pertama kali aplikasi dibuka
        DB::table('settings')->insert([
            [
                'key' => 'default_discount',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'default_tax',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
