<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Добавляем новые поля
            $table->date('start_date')->nullable()->after('price');
            $table->integer('max_students')->nullable()->after('start_date');
            $table->boolean('is_active')->default(true)->after('max_students');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'max_students', 'is_active']);
        });
    }
};