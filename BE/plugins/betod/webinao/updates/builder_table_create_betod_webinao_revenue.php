<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoRevenue extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_revenue', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->date('sale_date')->nullable();
            $table->string('total_revenue')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_revenue');
    }
}
