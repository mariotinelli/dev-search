<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorite_developers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('developer_id')->constrained('developers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_developers');
    }
};
