<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoFilters extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_filters', function($table)
        {
            $table->string('code')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_filters', function($table)
        {
            $table->dropColumn('code');
        });
    }
}
