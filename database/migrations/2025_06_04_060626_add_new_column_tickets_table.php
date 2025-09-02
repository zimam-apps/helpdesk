<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'is_ticket_assign_to_agent')) {
                $table->string('is_ticket_assign_to_agent')->nullable()->after('status')->comment('Assined Means Assign , UnAssigned Means UnAssign');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'is_ticket_assign_to_agent')) {
                $table->dropColumn('is_ticket_assign_to_agent');
            }
        });
    }
};
