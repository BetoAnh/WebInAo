<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoOrderPositions extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_order_positions', function($table)
        {
            $table->integer('order_id')->unsigned()->change();
            $table->integer('product_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_order_positions', function($table)
        {
            $table->integer('order_id')->unsigned(false)->change();
            $table->integer('product_id')->unsigned(false)->change();
        });
    }
}
