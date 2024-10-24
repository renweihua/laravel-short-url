<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForbiddenToUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->boolean('is_forbidden')->unsigned()->default(0)->comment('是否禁用：0.否；1.是');
            $table->integer('forbidden_time')->unsigned()->default(0)->comment('禁用时间');
            $table->string('admin_remarks', 200)->default('')->comment('管理员备注');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropColumn('is_forbidden');
            $table->dropColumn('forbidden_time');
            $table->dropColumn('admin_remarks');
        });
    }
}
