<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            // $table->integer('status')->nullable()->default(0);
            $table->integer('quantity')->default(0);
            $table->enum('type', ['IN', 'OUT']);
            $table->string('reference')->nullable();
            // $table->string('description')->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();

            // Foreign Key Constraint
            $table->foreign('stock_id')
                ->references('id')
                ->on('stocks')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes for Performance
            $table->index('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('stock_movements');
    }
};
