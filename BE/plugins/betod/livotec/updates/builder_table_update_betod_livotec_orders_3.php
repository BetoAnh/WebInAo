<?php namespace Betod\Livotec\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateBetodLivotecOrders3 extends Migration
{
    public function up()
    {
        Schema::rename('betod_livotec_order', 'betod_livotec_orders');
    }
    
    public function down()
    {
        Schema::rename('betod_livotec_orders', 'betod_livotec_order');
    }
}
