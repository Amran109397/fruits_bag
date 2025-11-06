<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments_made', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->index(); 
            $table->bigInteger('bill_id')->nullable()->index(); 
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['draft','posted'])->default('posted')->index();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments_made'); }
};
