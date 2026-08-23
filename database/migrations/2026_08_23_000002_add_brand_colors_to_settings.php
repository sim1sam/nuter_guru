<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('brand_brown', 20)->nullable()->after('theme_two');
            $table->string('accent_color', 20)->nullable()->after('brand_brown');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['brand_brown', 'accent_color']);
        });
    }
};
