<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->string('service_fee', 10)->nullable()->default(0);
            $table->string('tax', 10)->nullable()->default(0);
            $table->decimal('sub_total')->default(0);
            $table->decimal('total')->default(0);
            $table->string('transaction_id')->nullable()->default(null);
            $table->string('payment_status')->nullable()->default('pending');
            $table->string('shipment_status')->nullable()->default('processing');
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
        Schema::dropIfExists('orders');
    }
}
