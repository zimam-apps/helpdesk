<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'subcategory')) {
                $table->string('subcategory')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('subcategory');
        });
    }

};
