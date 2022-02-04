<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->string('name', 50)->nullable()->default();
            $table->string('addressline1')->nullable()->default();
            $table->string('addressline2')->nullable()->default();
            $table->string('nearby')->nullable()->default();
            $table->string('pincode', 50)->nullable()->default();
            $table->string('phone', 50)->nullable()->default();
            $table->string('email', 80)->nullable()->default();
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
        Schema::dropIfExists('user_addresses');
    }
}
