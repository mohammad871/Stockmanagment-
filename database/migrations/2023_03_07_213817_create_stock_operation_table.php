<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_operation', function (Blueprint $table) {
            $table->id();
            $table->enum("stock_operation_type", ["PURCHASE", "SELL", "RETURN"]);
            $table->string("bill_number", 15);
            $table->integer("quantity");
            $table->foreignId("stock_id")
                ->constrained("stock")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("item_id")
                ->nullable()
                ->constrained("items")
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId("customer")
                ->nullable()
                ->constrained("customers")
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('hijri_date', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_operation');
    }


};
