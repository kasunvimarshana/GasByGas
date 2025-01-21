<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            $table->integer('status')->nullable()->default(0); // ['PENDING', 'APPROVED', 'CONFIRMED', 'CANCELLED', 'REJECTED', 'COMPLETED']
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('reference')->nullable();
            $table->string('description')->nullable();
            $table->string('token')->unique()->nullable();
            $table->timestamp('expected_pickup_date')->nullable();
            $table->integer('tolerance_period_in_days')->default(0);
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('related_entity_id'); // Related model's ID
            $table->string('related_entity_type');           // Related model's type

            // Foreign Key Constraint
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes for Performance
            $table->index('company_id');
            $table->index(['related_entity_id', 'related_entity_type'], 'related_entity_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
//
