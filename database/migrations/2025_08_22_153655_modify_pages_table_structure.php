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
        Schema::table('pages', function (Blueprint $table) {
            // Pastikan kolom title ada dengan panjang yang benar
            if (!Schema::hasColumn('pages', 'title')) {
                $table->string('title', 150)->after('id');
            }
            
            // Pastikan kolom slug ada dan unique
            if (!Schema::hasColumn('pages', 'slug')) {
                $table->string('slug', 200)->unique()->after('title');
            }
            
            // Pastikan kolom body ada
            if (!Schema::hasColumn('pages', 'body')) {
                $table->text('body')->after('slug');
            }
            
            // Pastikan kolom status ada dengan enum yang benar
            if (!Schema::hasColumn('pages', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('body');
            }
            
            // Pastikan kolom foto ada
            if (!Schema::hasColumn('pages', 'foto')) {
                $table->string('foto', 255)->nullable()->after('status');
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
