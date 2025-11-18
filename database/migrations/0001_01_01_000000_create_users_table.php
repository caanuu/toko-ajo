<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // 1. ID (Sesuai PDF: Integer PK)
            $table->id();

            // 2. Name (Sesuai PDF: Varchar 50)
            $table->string('name', 50);

            // 3. Email (Sesuai PDF: Varchar 50, Unique)
            $table->string('email', 50)->unique();

            // Bawaan Laravel (Biarkan saja, berguna untuk verifikasi)
            $table->timestamp('email_verified_at')->nullable();

            // 4. Password (PDF minta 50, tapi WAJIB minimal 60 untuk Hash Laravel)
            // Kita pakai default string() agar aman (255 karakter)
            $table->string('password');

            // 5. Token (Sesuai PDF: Varchar 100)
            $table->rememberToken();

            // 6. Timestamps (Created_at & Updated_at)
            $table->timestamps();

            // TAMBAHAN PENTING (Berdasarkan Struktur Organisasi Halaman 2)
            // Agar bisa membedakan login Pemilik vs Gudang vs Pemasaran
            $table->enum('role', ['owner', 'admin', 'gudang', 'pemasaran', 'produksi', 'karyawan'])
                ->default('karyawan');
        });

        // Tabel di bawah ini (reset token & sessions) adalah bawaan Laravel.
        // Tidak ada di PDF, tapi SANGAT DISARANKAN JANGAN DIHAPUS
        // agar fitur "Lupa Password" dan "Login Session" berjalan lancar.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
