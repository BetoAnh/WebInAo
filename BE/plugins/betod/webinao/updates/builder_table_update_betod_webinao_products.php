<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoProducts extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->integer('post_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->dropColumn('post_id');
        });
    }
}
