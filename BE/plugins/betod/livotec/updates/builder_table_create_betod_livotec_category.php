<?php namespace Betod\Livotec\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodLivotecCategory extends Migration
{
    public function up()
    {
        Schema::create('betod_livotec_category', function($table)
        {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->string('description')->nullable();
            $table->text('property')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_livotec_category');
    }
}
