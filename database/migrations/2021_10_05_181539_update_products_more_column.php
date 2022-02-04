<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsMoreColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function ($table) {
            $table->integer('brand_id')->after('category_id')->nullable()->default(0);
            $table->string('age_statement', 50)->after('brand_id')->nullable()->default();
            $table->integer('distillery_id')->after('age_statement')->nullable()->default(0);
            $table->string('year_of_release', 50)->after('distillery_id')->nullable()->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
