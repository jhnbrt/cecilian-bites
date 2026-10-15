<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('orders', function(Blueprint $table) {
  $table->string('payment_method', 12)->nullable(); $table->string('payment_reference', 100)->nullable();
  $table->decimal('discount',10,2)->default(0); $table->decimal('amount_paid',10,2)->nullable();
  $table->timestamp('refunded_at')->nullable(); $table->foreignId('refunded_by')->nullable()->constrained('users')->nullOnDelete();
  $table->string('refund_reason',500)->nullable(); $table->timestamp('canceled_at')->nullable();
 }); }
 public function down(): void { Schema::table('orders',function(Blueprint $table) { $table->dropConstrainedForeignId('refunded_by'); $table->dropColumn(['payment_method','payment_reference','discount','amount_paid','refunded_at','refund_reason','canceled_at']); }); }
};
