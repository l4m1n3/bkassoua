<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('address');
            $table->text('password');                    // déjà hashé
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('phone_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_registrations');
    }
};