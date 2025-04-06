<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoOrderPositions extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_order_positions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->text('variant')->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_order_positions');
    }
}
