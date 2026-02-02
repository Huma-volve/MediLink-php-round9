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
        Schema::table('patients', function (Blueprint $table) {


            $table->enum('gender', ['male', 'female'])
                ->nullable()
                ->after('date_of_birth');


            $table->unique('user_id');


            $table->dropForeign(['insurance_id']);
            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->dropUnique(['user_id']);

            $table->dropForeign(['insurance_id']);
            $table->foreign('insurance_id')
                ->references('id')
                ->on('insurances')
                ->onDelete('cascade');

            $table->dropColumn('gender');
        });
    }
};
