<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_account_id')->index(); 
            $table->date('date');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2); 
            $table->string('reference')->nullable()->index();
            $table->timestamp('reconciled_at')->nullable()->index();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bank_transactions'); }
};
