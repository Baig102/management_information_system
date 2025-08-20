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
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            //$table->foreignId('other_charges_id')->nullable()->references('id')->on('booking_other_charges');//->onDelete('cascade');
            $table->tinyInteger('payment_type');
            $table->foreignId('installment_id')->nullable()->references('id')->on('booking_installment_plans');
            $table->integer('installment_no')->unsigned()->nullable()->default(0);
            $table->float('total_amount', 15,2)->nullable();
            $table->float('reciving_amount', 15, 2)->nullable();
            $table->float('remaining_amount', 15, 2)->nullable();
            $table->string('payment_method', 100);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_branch', 100)->nullable();
            $table->date('office_date')->nullable();
            $table->string('card_type', 100)->nullable();
            $table->string('card_number', 100)->nullable();
            $table->string('card_holder_name', 100)->nullable();
            $table->string('card_expiry_date', 100)->nullable();
            $table->integer('card_cvc')->unsigned()->nullable();
            $table->float('cc_charges', 15, 2)->unsigned()->nullable();
            $table->date('deposit_date')->nullable();
            $table->date('due_date')->nullable();
            $table->dateTime('payment_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->softDeletes(); // This adds a deleted_at column for soft deletes
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
