<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments_received', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->index(); // no FK
            $table->bigInteger('invoice_id')->nullable()->index(); // no FK
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['draft','posted'])->default('posted')->index();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments_received'); }
};
