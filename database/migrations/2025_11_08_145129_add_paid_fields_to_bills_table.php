<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('bills', 'status')) {
                $table->string('status', 20)->default('draft')->after('paid_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (Schema::hasColumn('bills', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
            if (Schema::hasColumn('bills', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
