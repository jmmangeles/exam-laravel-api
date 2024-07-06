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
        Schema::create('client_accesses', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('device_uuid', 150)->nullable();
            $table->string('device_os', 50)->nullable();
            $table->string('access_token', 1000);
            $table->string('fcm_token', 250)->nullable();
            $table->string('ip_address', 50);
            $table->timestamp('last_logged_in')->nullable();
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
        Schema::dropIfExists('client_accesses');
    }
};
