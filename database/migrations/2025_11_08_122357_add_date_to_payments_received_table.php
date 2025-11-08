<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments_received', function (Blueprint $table) {
            if (!Schema::hasColumn('payments_received', 'date')) {
                $table->date('date')->after('amount')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments_received', function (Blueprint $table) {
            if (Schema::hasColumn('payments_received', 'date')) {
                $table->dropColumn('date');
            }
        });
    }
};
