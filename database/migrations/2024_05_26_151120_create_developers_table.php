<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id();

            $table->string('login');
            $table->string('name');
            $table->string('avatar_url');
            $table->string('url');
            $table->string('location');

            $table->string('email')->nullable();
            $table->text('bio')->nullable();

            $table->integer('followers')->default(0);
            $table->integer('repos')->default(0);
            $table->integer('stars')->default(0);
            $table->integer('commits')->default(0);
            $table->integer('repos_contributions')->default(0);
            $table->integer('score')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
