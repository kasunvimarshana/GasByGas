<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('company_products', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            // $table->integer('status')->nullable()->default(0);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            // Foreign Key Constraint
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes for Performance
            $table->index('company_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('company_products');
    }
};
