<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoProducts4 extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->integer('avaiable');
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->dropColumn('avaiable');
        });
    }
}
