<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name');
            }
            if (! Schema::hasColumn('products', 'short_name_bn')) {
                $table->string('short_name_bn')->nullable()->after('short_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'name_bn')) {
                $table->dropColumn('name_bn');
            }
            if (Schema::hasColumn('products', 'short_name_bn')) {
                $table->dropColumn('short_name_bn');
            }
        });
    }
};
