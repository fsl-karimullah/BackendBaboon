<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('instance');
            $table->string('avatar')->nullable();
            $table->string('phone_number');
            $table->string('email')->unique(); 
            $table->string('password');
            $table->dateTime('subscription_exp_date')->nullable();
            $table->boolean('is_subscribed')->default_false('subscription_exp_date >= 2023/04/16');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
