<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 12, 0);
            $table->decimal('sale_price', 12, 0)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('featured')->default(false);
            // size_options: JSON [{"key":"small","label":"Nhỏ (16cm)","price":250000}, ...]
            $table->json('size_options')->nullable();
            // số ngày tối thiểu phải đặt trước (vd: bánh sinh nhật custom = 3 ngày)
            $table->unsignedTinyInteger('min_lead_days')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
