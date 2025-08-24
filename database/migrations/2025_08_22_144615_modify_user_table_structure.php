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
        Schema::table('user', function (Blueprint $table) {
            // Pastikan kolom id_user ada dan sebagai primary key
            if (!Schema::hasColumn('user', 'id_user')) {
                $table->id('id_user')->first();
            }
            
            // Pastikan kolom name ada
            if (!Schema::hasColumn('user', 'name')) {
                $table->string('name')->after('id_user');
            }
            
            // Pastikan kolom email ada dan unique
            if (!Schema::hasColumn('user', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            
            // Pastikan kolom password ada
            if (!Schema::hasColumn('user', 'password')) {
                $table->string('password')->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback untuk migration ini
    }
};
