<?php

use App\Models\Employee;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title', 5);
            $table->string('name', 100);
            $table->string('picture', 100)->nullable()->default('user-dummy-img.jpg');
            $table->string('guardian_name', 100);
            $table->string('personal_email', 50)->nullable();
            $table->string('personal_phone', 15)->unique();
            $table->string('cnic', 15)->unique();
            $table->string('gender', 10);
            $table->date('dob')->nullable();
            $table->boolean('marital_status')->nullable()->default(false);
            $table->string('employment_type', 100)->default('Permanent');
            $table->string('designation', 100);
            // $table->string('department', 100);
            $table->string('official_email', 50)->unique();
            $table->string('official_phone', 15)->nullable();
            $table->string('experience', 100)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('address')->nullable();
            $table->integer('zip_code')->nullable();
            $table->date('joining_date');
            $table->date('resigning_date')->nullable();
            $table->float('basic_salary')->nullable()->default(0.00);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
        Employee::create([
            'code'                  => '0001',
            'title'                 => 'Mr.',
            'name'                  => 'Umair Mehmood Khan Lodhi',
            'guardian_name'         => 'Mehmood Khan',
            'personal_email'        => 'info.devumair@gmail.com',
            'personal_phone'        => '00923085551515',
            'cnic'                  => '34502-3628748-7',
            'gender'                => 'Male',
            'dob'                   => '1990-09-16',
            'marital_status'        => true,
            'employment_type'       => 'Permanent',
            'designation'           => 'Software Engineer',
            'official_email'        => 'info.devumair@gmail.com',
            'official_phone'        => '00923085551515',
            'experience'            => '10 Years',
            'education'             => 'BSCS',
            'religion'              => 'Islam',
            'blood_group'           => 'A+',
            'city'                  => 'Lahore',
            'state'                 => 'Punjab',
            'address'               => 'Lahore Pakistan',
            'zip_code'              => 54000,
            'joining_date'          => '2023-12-01',
            'basic_salary'          => '0.00',
            'is_active'             => true,
            'created_at'            => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
