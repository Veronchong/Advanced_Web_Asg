<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageUrlToPetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('status');
        });
    }
    
    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
}
