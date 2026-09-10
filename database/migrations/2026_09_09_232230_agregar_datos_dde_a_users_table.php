<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('codigo_rda', 50)
                ->unique()
                ->after('id');

            $table->string('rol', 20)
                ->default('Usuario')
                ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['codigo_rda']);
            $table->dropColumn(['codigo_rda', 'rol']);
        });
    }
};
