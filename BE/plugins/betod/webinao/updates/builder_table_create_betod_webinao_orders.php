<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoOrders extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_orders', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status_id')->nullable()->default(0);
            $table->text('property')->nullable();
            $table->integer('price')->nullable();
            $table->string('order_code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_orders');
    }
}
