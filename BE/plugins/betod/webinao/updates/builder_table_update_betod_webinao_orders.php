<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoOrders extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_orders', function($table)
        {
            $table->string('order_code')->unique();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_orders', function($table)
        {
            $table->string('order_code')->unique();
        });
    }
}
