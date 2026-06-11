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
        Schema::create('members', function (Blueprint $table) {
            $table->id(); 
            
            
            $table->string('name');
            $table->string('gender');
            $table->date('dateOfBirth');
            $table->string('email')->unique(); 
            $table->string('phoneNumber');
            $table->string('address');
            $table->string('maritalStatus');
            $table->string('baptismStatus');
            $table->integer('membershipNumber')->unique(); 
            $table->date('joinDate');
            $table->string('activeStatus');
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
