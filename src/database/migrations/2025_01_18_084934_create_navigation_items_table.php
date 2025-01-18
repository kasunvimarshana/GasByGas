<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            // $table->integer('status')->nullable()->default(0);
            $table->string('title'); // Label of the navigation item
            $table->string('icon')->nullable(); // Icon class
            $table->string('route')->nullable(); // Route name
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent menu reference
            $table->integer('order')->default(0); // Ordering
            $table->text('parameters')->nullable(); // (JSON) Optional parameters for routes
            $table->string('permission')->nullable(); // Required permission e.g., admin, user
            $table->text('types')->nullable(); // (JSON) Stores locations like sidebar, left_sidebar, right_sidebar, main_navigation, breadcrumbs etc.
            $table->boolean('is_active')->default(true);

            // Foreign Key Constraint
            $table->foreign('parent_id')
                ->references('id')
                ->on('navigation_items')
                // ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes for Performance
            $table->index('parent_id');
            $table->index('order');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        // $table->dropSoftDeletes();
        Schema::dropIfExists('navigation_items');
    }
};

