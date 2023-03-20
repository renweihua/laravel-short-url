<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrlClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'url_clicks';
        if (Schema::hasTable($table)) return;
        Schema::create($table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('url_id')->unsigned()->default(0)->comment('Url的Id');
            $table->integer('click')->unsigned()->default(0)->comment('');
            $table->integer('real_click')->unsigned()->default(0)->comment('');
            $table->string('country', 200)->default('')->comment('国家');
            $table->string('country_full', 200)->default('')->comment('国家');
            $table->string('referer', 300)->default('')->comment('来源');
            $table->string('ip_address', 300)->default('')->comment('IP');
            $table->tinyInteger('ip_hashed')->default(0)->comment('IP');
            $table->tinyInteger('ip_anonymized')->default(0)->comment('IP');
            $table->integer('created_time')->unsigned()->default(0)->comment('创建时间');
            $table->integer('updated_time')->unsigned()->default(0)->comment('更新时间');
            $table->boolean('is_delete')->unsigned()->default(0)->comment('是否删除');
            $table->index('url_id');
            $table->index('is_delete');
        });
        $table = get_db_prefix() . $table;
        // 设置表注释
        DB::statement("ALTER TABLE `{$table}` comment 'Url访问记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_clicks');
    }
}
