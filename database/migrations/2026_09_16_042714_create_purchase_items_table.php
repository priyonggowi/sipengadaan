<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('purchase_requests')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable()->comment('Spesifikasi/keterangan');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->default('pcs')->comment('pcs, rim, lembar, meter, unit');
            $table->decimal('estimated_price', 15, 2)->default(0);
            $table->decimal('actual_price', 15, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
