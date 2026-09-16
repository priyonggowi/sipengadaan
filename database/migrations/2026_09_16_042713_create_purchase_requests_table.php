<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->comment('PR-2024-001');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'draft',
                'pending_manager',
                'pending_director',
                'approved',
                'rejected',
                'purchased',
                'received',
                'cancelled',
            ])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->decimal('total_estimated', 15, 2)->default(0);
            $table->decimal('total_actual', 15, 2)->default(0);
            $table->date('needed_date')->nullable()->comment('Tanggal dibutuhkan');
            $table->date('purchased_date')->nullable()->comment('Tanggal dibeli');
            $table->date('received_date')->nullable()->comment('Tanggal diterima');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
