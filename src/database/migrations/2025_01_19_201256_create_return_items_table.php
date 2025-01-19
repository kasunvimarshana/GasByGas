<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            // $table->integer('status')->nullable()->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedBigInteger('item_return_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            // Foreign Key Constraint
            $table->foreign('item_return_id')
                ->references('id')
                ->on('item_returns')
                // ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes for Performance
            $table->index('item_return_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('return_items');
    }
};
