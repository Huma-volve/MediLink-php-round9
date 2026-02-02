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
        Schema::create('help_items', function (Blueprint $table) {
            $table->id();
            $table->string('faq_url');
            $table->string('contact_support_url');
            $table->string('documentation_url');
            $table->string('video_tutorials_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_items');
    }
};
