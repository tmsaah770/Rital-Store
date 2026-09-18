<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name' => 'القاهرة', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'الجيزة', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'الاسكندرية', 'shipping_cost' => 85, 'delivery_days' => 3],
            ['name' => 'بورسعيد', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'السويس', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'الاسماعيلية', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'الشرقية', 'shipping_cost' => 65, 'delivery_days' => 2],
            ['name' => 'الدقهلية', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'دمياط', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'القليوبية', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'كفر الشيخ', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'الغربية', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'المنوفية', 'shipping_cost' => 80, 'delivery_days' => 3],
            ['name' => 'البحيرة', 'shipping_cost' => 80, 'delivery_days' => 4],
            ['name' => 'بني سويف', 'shipping_cost' => 100, 'delivery_days' => 5],
            ['name' => 'الفيوم', 'shipping_cost' => 100, 'delivery_days' => 5],
            ['name' => 'المنيا', 'shipping_cost' => 100, 'delivery_days' => 5],
            ['name' => 'اسيوط', 'shipping_cost' => 100, 'delivery_days' => 5],
            ['name' => 'سوهاج', 'shipping_cost' => 100, 'delivery_days' => 5],
            ['name' => 'قنا', 'shipping_cost' => 110, 'delivery_days' => 7],
            ['name' => 'الاقصر', 'shipping_cost' => 110, 'delivery_days' => 7],
            ['name' => 'اسوان', 'shipping_cost' => 110, 'delivery_days' => 7],
            ['name' => 'البحر الاحمر', 'shipping_cost' => 170, 'delivery_days' => 7],
            ['name' => 'الوادي الجديد', 'shipping_cost' => 170, 'delivery_days' => 7],
            ['name' => 'مطروح', 'shipping_cost' => 170, 'delivery_days' => 8],
            ['name' => 'شمال سيناء', 'shipping_cost' => 170, 'delivery_days' => 8],
            ['name' => 'جنوب سيناء', 'shipping_cost' => 170, 'delivery_days' => 8],
            ['name' => 'المدن الجديده', 'shipping_cost' => 85, 'delivery_days' => 3],
        ];

        foreach ($governorates as $gov) {
            \App\Models\Governorate::updateOrCreate(['name' => $gov['name']], $gov);
        }
    }
}
