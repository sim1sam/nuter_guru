<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'stock_deducted_at')) {
                $table->timestamp('stock_deducted_at')->nullable()->after('order_declined_date');
            }
        });

        // Legacy orders already deducted stock at create time.
        if (Schema::hasColumn('orders', 'stock_deducted_at')) {
            DB::table('orders')
                ->whereNull('stock_deducted_at')
                ->whereNull('stock_restored_at')
                ->update([
                    'stock_deducted_at' => DB::raw('COALESCE(order_approval_date, created_at)'),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'stock_deducted_at')) {
                $table->dropColumn('stock_deducted_at');
            }
        });
    }
};
