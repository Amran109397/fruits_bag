<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->index(); 
            $table->string('bill_number')->unique();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft','posted'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->index(['status','posted_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('bills'); }
};
