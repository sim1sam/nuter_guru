<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_variants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('weight_in_kg', 15, 3);
            $table->unsignedInteger('weight_in_gram');
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('weight_variants')->insert([
            [
                'name' => '250g',
                'code' => '250g',
                'weight_in_kg' => 0.250,
                'weight_in_gram' => 250,
                'status' => 1,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '500g',
                'code' => '500g',
                'weight_in_kg' => 0.500,
                'weight_in_gram' => 500,
                'status' => 1,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => '1kg',
                'code' => '1kg',
                'weight_in_kg' => 1.000,
                'weight_in_gram' => 1000,
                'status' => 1,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        Schema::create('product_weight_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('weight_variant_id');
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'weight_variant_id'], 'product_weight_variant_unique');
            $table->index('weight_variant_id');
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'unit_type')) {
                $table->string('unit_type', 10)->default('pcs')->after('qty');
            }
            if (! Schema::hasColumn('products', 'selling_price_mode')) {
                $table->string('selling_price_mode', 20)->default('automatic')->after('unit_type');
            }
        });

        DB::statement('ALTER TABLE products MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE warehouse_stocks MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_movements MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_movements MODIFY qty_before DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE stock_movements MODIFY qty_after DECIMAL(15,3) NOT NULL DEFAULT 0');

        Schema::table('stock_movements', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_movements', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('stock_movements', 'unit')) {
                $table->string('unit', 10)->nullable()->after('qty');
            }
            if (! Schema::hasColumn('stock_movements', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('unit');
            }
            if (! Schema::hasColumn('stock_movements', 'variant_name')) {
                $table->string('variant_name')->nullable()->after('base_quantity');
            }
            if (! Schema::hasColumn('stock_movements', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name');
            }
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_order_items', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('purchase_order_items', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('line_total');
            }
            if (! Schema::hasColumn('purchase_order_items', 'variant_name')) {
                $table->string('variant_name')->nullable()->after('base_quantity');
            }
            if (! Schema::hasColumn('purchase_order_items', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name');
            }
            if (! Schema::hasColumn('purchase_order_items', 'weight_in_gram')) {
                $table->unsignedInteger('weight_in_gram')->nullable()->after('unit_weight_kg');
            }
        });

        DB::statement('ALTER TABLE purchase_order_items MODIFY ordered_qty DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE purchase_order_items MODIFY received_qty DECIMAL(15,3) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE purchase_order_items MODIFY returned_qty DECIMAL(15,3) NOT NULL DEFAULT 0');

        Schema::table('purchase_receipt_items', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_receipt_items', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('purchase_receipt_items', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('unit_cost');
            }
            if (! Schema::hasColumn('purchase_receipt_items', 'variant_name')) {
                $table->string('variant_name')->nullable()->after('base_quantity');
            }
            if (! Schema::hasColumn('purchase_receipt_items', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name');
            }
        });
        DB::statement('ALTER TABLE purchase_receipt_items MODIFY received_qty DECIMAL(15,3) NOT NULL DEFAULT 0');

        Schema::table('purchase_return_items', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_return_items', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('purchase_return_items', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('unit_cost');
            }
            if (! Schema::hasColumn('purchase_return_items', 'variant_name')) {
                $table->string('variant_name')->nullable()->after('base_quantity');
            }
            if (! Schema::hasColumn('purchase_return_items', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name');
            }
        });
        DB::statement('ALTER TABLE purchase_return_items MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 0');

        Schema::table('shopping_carts', function (Blueprint $table) {
            if (! Schema::hasColumn('shopping_carts', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('shopping_carts', 'variant_name_snapshot')) {
                $table->string('variant_name_snapshot')->nullable()->after('weight_variant_id');
            }
            if (! Schema::hasColumn('shopping_carts', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name_snapshot');
            }
            if (! Schema::hasColumn('shopping_carts', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('unit_weight_kg');
            }
            if (! Schema::hasColumn('shopping_carts', 'unit_price')) {
                $table->decimal('unit_price', 15, 2)->nullable()->after('base_quantity');
            }
        });
        DB::statement('ALTER TABLE shopping_carts MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 1');

        Schema::table('order_products', function (Blueprint $table) {
            if (! Schema::hasColumn('order_products', 'weight_variant_id')) {
                $table->unsignedBigInteger('weight_variant_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('order_products', 'variant_name_snapshot')) {
                $table->string('variant_name_snapshot')->nullable()->after('weight_variant_id');
            }
            if (! Schema::hasColumn('order_products', 'unit_weight_kg')) {
                $table->decimal('unit_weight_kg', 15, 3)->nullable()->after('variant_name_snapshot');
            }
            if (! Schema::hasColumn('order_products', 'weight_in_gram')) {
                $table->unsignedInteger('weight_in_gram')->nullable()->after('unit_weight_kg');
            }
            if (! Schema::hasColumn('order_products', 'base_quantity')) {
                $table->decimal('base_quantity', 15, 3)->nullable()->after('qty');
            }
            if (! Schema::hasColumn('order_products', 'unit_cost')) {
                $table->decimal('unit_cost', 15, 4)->nullable()->after('unit_price');
            }
        });
        DB::statement('ALTER TABLE order_products MODIFY qty DECIMAL(15,3) NOT NULL DEFAULT 0');

        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->unsignedBigInteger('order_id');
            $table->string('status', 30)->default('posted');
            $table->date('return_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->index('order_id');
        });

        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_return_id');
            $table->unsignedBigInteger('order_product_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('weight_variant_id')->nullable();
            $table->string('variant_name_snapshot')->nullable();
            $table->decimal('unit_weight_kg', 15, 3)->nullable();
            $table->decimal('qty', 15, 3);
            $table->decimal('base_quantity', 15, 3);
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->timestamps();
            $table->index('order_return_id');
            $table->index('product_id');
        });

        DB::table('products')->whereNull('unit_type')->orWhere('unit_type', '')->update(['unit_type' => 'pcs']);
        DB::table('products')->whereNull('selling_price_mode')->orWhere('selling_price_mode', '')->update(['selling_price_mode' => 'automatic']);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_return_items');
        Schema::dropIfExists('order_returns');
        Schema::dropIfExists('product_weight_variants');
        Schema::dropIfExists('weight_variants');

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'selling_price_mode')) {
                $table->dropColumn('selling_price_mode');
            }
            if (Schema::hasColumn('products', 'unit_type')) {
                $table->dropColumn('unit_type');
            }
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            foreach (['weight_variant_id', 'unit', 'base_quantity', 'variant_name', 'unit_weight_kg'] as $col) {
                if (Schema::hasColumn('stock_movements', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            foreach (['weight_variant_id', 'base_quantity', 'variant_name', 'unit_weight_kg', 'weight_in_gram'] as $col) {
                if (Schema::hasColumn('purchase_order_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('purchase_receipt_items', function (Blueprint $table) {
            foreach (['weight_variant_id', 'base_quantity', 'variant_name', 'unit_weight_kg'] as $col) {
                if (Schema::hasColumn('purchase_receipt_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('purchase_return_items', function (Blueprint $table) {
            foreach (['weight_variant_id', 'base_quantity', 'variant_name', 'unit_weight_kg'] as $col) {
                if (Schema::hasColumn('purchase_return_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('shopping_carts', function (Blueprint $table) {
            foreach (['weight_variant_id', 'variant_name_snapshot', 'unit_weight_kg', 'base_quantity', 'unit_price'] as $col) {
                if (Schema::hasColumn('shopping_carts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('order_products', function (Blueprint $table) {
            foreach (['weight_variant_id', 'variant_name_snapshot', 'unit_weight_kg', 'weight_in_gram', 'base_quantity', 'unit_cost'] as $col) {
                if (Schema::hasColumn('order_products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
