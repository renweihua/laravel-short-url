<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;

class CreateDeviceTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'device_targets';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('url_id')->unsigned()->default(0)->comment('Url的Id');
            $table->integer('device_id')->unsigned()->default(0)->comment('设备编号枚举Id');
            $table->string('target_url', 200)->default('')->comment('对应设置的URL');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index('url_id');
            $table->index('device_id');
            $table->index('is_delete');
        });
        $table = get_db_prefix() . $table;
        // 设置表注释
        DB::statement("ALTER TABLE `{$table}` comment '不同设备的域名配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_targets');
    }
}
