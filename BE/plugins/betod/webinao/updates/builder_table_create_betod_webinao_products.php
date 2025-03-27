<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoProducts extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_products', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('name');
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
            $table->text('image_url')->nullable();
            $table->text('slug');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_products');
    }
}
