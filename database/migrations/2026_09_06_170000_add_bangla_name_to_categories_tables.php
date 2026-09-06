<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name');
            }
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('sub_categories', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name');
            }
        });

        $categoryNames = [
            'Dry Fruits' => 'শুকনো ফল',
            'Nuts' => 'বাদাম',
            'Spices' => 'মসলা',
            'Seeds' => 'বীজ',
            'Honey' => 'মধু',
            'Dates' => 'খেজুর',
            'Cosmetics' => 'প্রসাধনী',
        ];

        foreach ($categoryNames as $en => $bn) {
            DB::table('categories')->where('name', $en)->whereNull('name_bn')->update(['name_bn' => $bn]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'name_bn')) {
                $table->dropColumn('name_bn');
            }
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            if (Schema::hasColumn('sub_categories', 'name_bn')) {
                $table->dropColumn('name_bn');
            }
        });
    }
};
