<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoProducts5 extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->integer('sold_out')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->dropColumn('sold_out');
        });
    }
}
