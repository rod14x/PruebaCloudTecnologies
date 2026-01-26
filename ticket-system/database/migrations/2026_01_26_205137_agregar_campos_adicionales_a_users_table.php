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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 20)->unique()->after('email');
            $table->string('telefono', 20)->after('dni');
            $table->string('codigo_recuperacion')->nullable()->after('remember_token');
            $table->timestamp('codigo_recuperacion_expira')->nullable()->after('codigo_recuperacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni', 'telefono', 'codigo_recuperacion', 'codigo_recuperacion_expira']);
        });
    }
};
