<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('steadfast_settings')) {
            Schema::create('steadfast_settings', function (Blueprint $table) {
                $table->id();
                $table->tinyInteger('status')->default(0);
                $table->string('api_key')->nullable();
                $table->string('secret_key')->nullable();
                $table->string('base_url')->default('https://portal.packzy.com/api/v1');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'steadfast_consignment_id')) {
                    $table->string('steadfast_consignment_id')->nullable()->after('payment_screenshot');
                }
                if (! Schema::hasColumn('orders', 'steadfast_tracking_code')) {
                    $table->string('steadfast_tracking_code')->nullable()->after('steadfast_consignment_id');
                }
                if (! Schema::hasColumn('orders', 'steadfast_status')) {
                    $table->string('steadfast_status')->nullable()->after('steadfast_tracking_code');
                }
                if (! Schema::hasColumn('orders', 'steadfast_response')) {
                    $table->text('steadfast_response')->nullable()->after('steadfast_status');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('steadfast_settings');

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach (['steadfast_consignment_id', 'steadfast_tracking_code', 'steadfast_status', 'steadfast_response'] as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
