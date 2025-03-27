<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoProducts2 extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->text('property')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_products', function($table)
        {
            $table->dropColumn('property');
        });
    }
}
