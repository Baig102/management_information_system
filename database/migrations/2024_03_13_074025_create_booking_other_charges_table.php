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
        Schema::create('booking_other_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->references('id')->on('bookings');
            $table->foreignId('payment_id')->nullable()->references('id')->on('booking_payments');//->onDelete('cascade');
            $table->string('charges_type', 50);
            $table->float('amount', 15, 2);

            $table->float('reciving_amount', 15, 2)->nullable();
            $table->float('remaining_amount', 15, 2)->nullable();

            $table->text('comments');
            $table->date('charges_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('payment_status')->default(false)->comment('0->pending / partially paid,1->fully paid');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes(); // This adds a deleted_at column for soft deletes
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users');
            $table->integer('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_other_charges');
    }
};
