<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoVariants extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_variants', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('product_id');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('price')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_variants');
    }
}
