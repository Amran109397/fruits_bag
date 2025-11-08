<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bill_id')->index(); 
            $table->string('description');
            $table->decimal('qty', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('line_total_excl_tax', 15, 2)->default(0);
            $table->timestamps();
            
        });
    }
    public function down(): void { Schema::dropIfExists('bill_items'); }
};
