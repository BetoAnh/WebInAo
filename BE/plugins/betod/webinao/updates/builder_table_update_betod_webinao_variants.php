<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoVariants extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_variants', function($table)
        {
            $table->integer('product_id')->unsigned()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_variants', function($table)
        {
            $table->integer('product_id')->unsigned(false)->change();
        });
    }
}
