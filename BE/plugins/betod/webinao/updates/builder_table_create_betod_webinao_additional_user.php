<?php namespace Betod\Webinao\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodWebinaoAdditionalUser extends Migration
{
    public function up()
    {
        Schema::create('betod_webinao_additional_user', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('user_id');
            $table->string('phone');
            $table->integer('province');
            $table->integer('district');
            $table->integer('subdistrict');
            $table->string('address');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('betod_webinao_additional_user');
    }
}
