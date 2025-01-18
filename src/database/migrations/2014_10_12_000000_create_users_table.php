<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->bigIncrements('id')->unsigned();
            // $table->text('metadata')->nullable(); // json
            // $table->softDeletes();
            $table->timestamps();
            $table->integer('status')->nullable()->default(0);
            // $table->string('timezone')->nullable()->default('UTC');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique();
            $table->string('password')->default(Hash::make(env('DEFAULT_USER_PASSWORD', 'password')));
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // $table->unsignedBigInteger('company_id')->nullable();

            // // Foreign Key Constraint
            // $table->foreign('company_id')
            //     ->references('id')
            //     ->on('companies')
            //     // ->onUpdate('cascade')
            //     ->onDelete('cascade');

            // // Indexes for Performance
            // $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        // $table->dropSoftDeletes();
        Schema::dropIfExists('users');
    }
};
