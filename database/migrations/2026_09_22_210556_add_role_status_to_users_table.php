<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role: 'user' | 'admin'  (string, not integer)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('is_author');
            }
            // status: 1 = active, 0 = suspended
            if (!Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists('role');
            $table->dropColumnIfExists('status');
        });
    }
};
