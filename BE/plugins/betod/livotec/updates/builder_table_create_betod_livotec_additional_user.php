<?php
namespace Betod\Livotec\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBetodLivotecAdditionalUser extends Migration
{
    public function up()
    {
        Schema::create('betod_livotec_additional_user', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('phone')->nullable();
            $table->integer('province')->nullable();
            $table->integer('district')->nullable();
            $table->integer('subdistrict')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('betod_livotec_additional_user');
    }
}
