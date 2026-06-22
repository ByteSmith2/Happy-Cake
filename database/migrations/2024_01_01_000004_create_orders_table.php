<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->text('note')->nullable();
            // ngày khách muốn nhận bánh (đặt trước)
            $table->date('delivery_date');
            // lời nhắn ghi trên bánh (vd: "Chúc mừng sinh nhật Mai")
            $table->string('cake_message', 200)->nullable();
            $table->decimal('total_price', 15, 0);
            $table->enum('status', ['pending', 'confirmed', 'baking', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
