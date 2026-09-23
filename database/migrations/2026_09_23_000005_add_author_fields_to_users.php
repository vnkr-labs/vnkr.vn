<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->unique()->nullable()->after('name');
            $table->string('avatar', 255)->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('facebook_url', 255)->nullable()->after('bio');
            $table->string('twitter_url', 255)->nullable()->after('facebook_url');
            $table->boolean('is_author')->default(false)->after('twitter_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'avatar', 'bio', 'facebook_url', 'twitter_url', 'is_author']);
        });
    }
};
