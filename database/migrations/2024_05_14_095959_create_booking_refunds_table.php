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
        Schema::create('booking_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->references('id')->on('bookings');
            //$table->tinyInteger('payment_type');
            //$table->foreignId('installment_id')->nullable()->references('id')->on('booking_installment_plans');
            //$table->integer('installment_no')->unsigned()->nullable()->default(0);
            $table->float('paid_amount', 15, 2)->nullable();
            $table->float('refunded_amount', 15, 2)->nullable();
            $table->float('service_charges', 15, 2)->nullable();
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
            $table->date('refund_requeseted_on')->nullable();
            $table->integer('refund_status')->unsigned()->default(0)->comment('0 -> pending, 1 -> approved, 2 -> rejected');
            $table->dateTime('refunded_on')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('booking_refunds');
    }
};
