<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoProducts6 extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->integer('category_id')->unsigned()->change();
            $table->integer('post_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->integer('category_id')->unsigned(false)->change();
            $table->integer('post_id')->unsigned(false)->change();
        });
    }
}
