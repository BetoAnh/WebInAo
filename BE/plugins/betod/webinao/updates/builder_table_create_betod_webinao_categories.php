<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoCategories extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_categories', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->text('name');
            $table->text('slug');
            $table->integer('parent_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_categories');
    }
}
