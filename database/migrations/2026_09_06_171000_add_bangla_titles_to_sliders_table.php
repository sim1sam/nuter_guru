<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (! Schema::hasColumn('sliders', 'title_one_bn')) {
                $table->string('title_one_bn')->nullable()->after('title_one');
            }
            if (! Schema::hasColumn('sliders', 'title_two_bn')) {
                $table->text('title_two_bn')->nullable()->after('title_two');
            }
        });

        $translations = [
            'Premium Dry Fruits' => [
                'title_one_bn' => 'প্রিমিয়াম শুকনো ফল',
                'title_two_bn' => 'কিশমিশ, খেজুর, এপ্রিকট ও মিক্সড শুকনো ফল — ১০০% অর্গানিক',
            ],
            'Fresh Nuts & Peanuts' => [
                'title_one_bn' => 'তাজা বাদাম ও চিনাবাদাম',
                'title_two_bn' => 'বাদাম, কাজু, পেস্তা, আখরোট ও রোস্টেড চিনাবাদাম',
            ],
            'Organic Seeds & Spices' => [
                'title_one_bn' => 'অর্গানিক বীজ ও মসলা',
                'title_two_bn' => 'সূর্যমুখী বীজ, চিয়া, ফ্ল্যাক্স ও প্রাকৃতিক মসলা',
            ],
        ];

        foreach ($translations as $titleOne => $bn) {
            DB::table('sliders')
                ->where('title_one', $titleOne)
                ->whereNull('title_one_bn')
                ->update($bn);
        }
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'title_one_bn')) {
                $table->dropColumn('title_one_bn');
            }
            if (Schema::hasColumn('sliders', 'title_two_bn')) {
                $table->dropColumn('title_two_bn');
            }
        });
    }
};
