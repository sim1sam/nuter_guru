<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'payment_screenshot')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_screenshot')->nullable()->after('transection_id');
            });
        }

        if (Schema::hasTable('bank_payments')) {
            Schema::table('bank_payments', function (Blueprint $table) {
                if (! Schema::hasColumn('bank_payments', 'manual_payment_status')) {
                    $table->tinyInteger('manual_payment_status')->default(1)->after('cash_on_delivery_status');
                }
                if (! Schema::hasColumn('bank_payments', 'manual_payment_info')) {
                    $table->text('manual_payment_info')->nullable()->after('manual_payment_status');
                }
            });

            $defaultInfo = "bKash: 01XXXXXXXXX (Personal)\nNagad: 01XXXXXXXXX (Personal)\nSend money / Payment — use order amount exactly.";
            DB::table('bank_payments')->whereNull('manual_payment_info')->orWhere('manual_payment_info', '')->update([
                'manual_payment_info' => $defaultInfo,
                'manual_payment_status' => 1,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'payment_screenshot')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_screenshot');
            });
        }

        if (Schema::hasTable('bank_payments')) {
            Schema::table('bank_payments', function (Blueprint $table) {
                if (Schema::hasColumn('bank_payments', 'manual_payment_info')) {
                    $table->dropColumn('manual_payment_info');
                }
                if (Schema::hasColumn('bank_payments', 'manual_payment_status')) {
                    $table->dropColumn('manual_payment_status');
                }
            });
        }
    }
};
