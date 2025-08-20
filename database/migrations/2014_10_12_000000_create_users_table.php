<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->references('id')->on('employees');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('avatar');
            $table->integer('role')->unsigned()->default(5)->comment("1->Super Admin, 2->Admin, 3->Manager, 4->Assistant Manager / Team Lead, 5->User / Agent"); // 1->Super Admin, 2->Admin, 3->Manager, 4->Assistant Manager / Team Lead, 5->User / Agent
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            // $table->integer('created_by')->nullable();
            // $table->integer('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        User::create(['employee_id' => 1,'name' => 'Super Admin','email' => 'info.devumair@gmail.com','password' => Hash::make('12345678'),'email_verified_at'=>'2024-01-02 17:04:58','role' => '1','avatar' => 'avatar-1.jpg','created_at' => now(),]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
