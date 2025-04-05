<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodWebinaoAdditionalUser extends Migration
{
    public function up()
    {
        Schema::table('betod_webinao_additional_user', function($table)
        {
            $table->string('phone', 255)->nullable()->change();
            $table->integer('province')->nullable()->change();
            $table->integer('district')->nullable()->change();
            $table->integer('subdistrict')->nullable()->change();
            $table->string('address', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('betod_webinao_additional_user', function($table)
        {
            $table->string('phone', 255)->nullable(false)->change();
            $table->integer('province')->nullable(false)->change();
            $table->integer('district')->nullable(false)->change();
            $table->integer('subdistrict')->nullable(false)->change();
            $table->string('address', 255)->nullable(false)->change();
        });
    }
}
