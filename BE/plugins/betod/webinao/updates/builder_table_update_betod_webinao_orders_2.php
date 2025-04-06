<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoOrders2 extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_orders', function($table)
        {
            $table->integer('user_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_orders', function($table)
        {
            $table->integer('user_id')->unsigned(false)->change();
        });
    }
}
