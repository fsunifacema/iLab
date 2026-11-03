<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades_curriculares', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('curso')->nullable();
            $table->unsignedInteger('carga_horaria')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // coordenador que criou
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades_curriculares');
    }
};
