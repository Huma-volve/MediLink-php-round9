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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_version', 20);
            $table->string('company_name');
            $table->string('terms_url')->nullable();
            $table->string('privacy_url')->nullable();
            $table->string('license_url')->nullable();
            $table->string('release_notes_url')->nullable();
            $table->string('support_email')->nullable();
            $table->string('website_url')->nullable();
            $table->string('company_address')->nullable();
            $table->string('app_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
