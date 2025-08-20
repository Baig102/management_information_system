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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies');
            $table->string('booking_prefix', 5);
            $table->integer('booking_number');
            $table->date('booking_date')->nullable();
            $table->date('ticket_deadline')->nullable();
            $table->tinyInteger('booking_payment_term')->default(1)->comment('1->non-refundable,2->refundable');
            $table->string('title', 10);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->string('nationality')->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('post_code')->nullable();
            $table->text('flight_pnr')->nullable();
            $table->text('flight_pnr_ajax')->nullable();
            $table->text('comments')->nullable();
            $table->tinyInteger('trip_type')->comment('1-> oneway, 2-> return');
            $table->tinyInteger('flight_type')->comment('1-> direct, 2-> in-direct');
            $table->string('currency', 10);
            $table->tinyInteger('payment_type')->comment('1-> full payment, 2-> installment');
            $table->string('payment_method', 50);
            $table->float('total_sales_cost', 15,2);
            $table->float('total_net_cost', 15,2);
            $table->float('margin', 15,2);
            $table->tinyInteger('total_installment')->nullable();
            $table->float('deposite_amount', 15,2);
            $table->float('balance_amount', 15,2);
            $table->date('deposit_date')->nullable();
            //$table->integer('ticket_supplier')->unsigned()->nullable()->default(12);
            $table->foreignId('ticket_supplier_id')->nullable()->references('id')->on('vendors');
            $table->tinyInteger('ticket_status');
            $table->tinyInteger('payment_status');
            $table->tinyInteger('booking_status')->default(1);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
