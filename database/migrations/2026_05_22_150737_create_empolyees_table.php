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
        Schema::create('empolyees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pin');
            $table->string('empolyee_name');
            $table->string('designation');
            $table->float('phone');
            $table->string('guardian_name');
            $table->text('address');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empolyees');
    }
};