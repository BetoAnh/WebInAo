<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoFilters extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_filters', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('name')->nullable();
            $table->integer('category_id')->nullable()->unsigned();
            $table->text('options')->nullable();
            $table->string('type')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_filters');
    }
}
