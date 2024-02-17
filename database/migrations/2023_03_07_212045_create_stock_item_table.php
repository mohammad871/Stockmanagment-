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
        Schema::create('stock_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId("stock_id")
                ->constrained("stock")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("item_id")
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->integer("quantity");
            $table->string('hijri_date', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item');
    }
};
