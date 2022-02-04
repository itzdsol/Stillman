<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableMoreColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('app_version', 50)->after('api_token')->nullable()->default(null);
            $table->string('login_type', 50)->after('app_version')->nullable()->default('email');
            $table->string('profile_pic')->after('login_type')->nullable()->default(null);
            $table->string('fb_provider_id')->after('profile_pic')->nullable()->default(null);
            $table->string('is_email_verified', 50)->after('fb_provider_id')->nullable()->default('false');
            $table->string('device_id')->after('is_email_verified')->nullable()->default(null);
            $table->string('device_model')->after('device_id')->nullable()->default(null);
            $table->string('fb_uid')->after('device_model')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('app_version');
            $table->dropColumn('login_type');
            $table->dropColumn('profile_pic');
            $table->dropColumn('fb_provider_id');
            $table->dropColumn('device_id');
            $table->dropColumn('is_email_verified');
            $table->dropColumn('device_model');
            $table->dropColumn('fb_uid');
        });
    }
}
