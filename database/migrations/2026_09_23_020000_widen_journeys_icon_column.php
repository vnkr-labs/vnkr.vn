<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Widen journeys.icon from 10 → 60 chars
 * to support Bootstrap icon class names (e.g. bi-graph-up-arrow)
 * in addition to emoji.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journeys', function (Blueprint $table) {
            $table->string('icon', 60)->default('📚')->change();
        });
    }

    public function down(): void
    {
        Schema::table('journeys', function (Blueprint $table) {
            $table->string('icon', 10)->default('📚')->change();
        });
    }
};
