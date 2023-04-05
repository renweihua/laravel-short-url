<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDeviceTargetsEnumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'device_targets_enums';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 200)->default('')->comment('名称');
            $table->string('display_name', 200)->default('')->comment('展示名称');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index('name');
            $table->index('is_delete');
        });
        $table = get_db_prefix() . $table;
        // 设置表注释
        DB::statement("ALTER TABLE `{$table}` comment '设备编号枚举表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_targets_enums');
    }
}
