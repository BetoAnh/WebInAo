<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoCategories extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_categories', function($table)
        {
            $table->integer('parent_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_categories', function($table)
        {
            $table->integer('parent_id')->unsigned(false)->change();
        });
    }
}
