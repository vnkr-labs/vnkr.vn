<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_slots', function (Blueprint $table) {
            $table->id();
            $table->string('position', 50)->unique(); // sidebar, in_article, footer, header
            $table->string('label', 100);             // Tên hiển thị trong admin
            $table->text('code')->nullable();          // HTML/script code quảng cáo
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed vị trí mặc định
        \DB::table('ad_slots')->insert([
            ['position' => 'sidebar_top',    'label' => 'Sidebar — Top (300×250)',      'code' => null, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'sidebar_bottom', 'label' => 'Sidebar — Bottom (300×250)',   'code' => null, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'in_article',     'label' => 'In-article (sau đoạn 3)',       'code' => null, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'footer_banner',  'label' => 'Footer Banner (728×90)',        'code' => null, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'header_banner',  'label' => 'Header Banner (728×90)',        'code' => null, 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_slots');
    }
};
