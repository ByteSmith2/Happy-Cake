<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bánh sinh nhật',  'slug' => 'banh-sinh-nhat',  'description' => 'Bánh kem sinh nhật custom theo yêu cầu, đặt trước tối thiểu 3 ngày'],
            ['name' => 'Bánh kem',        'slug' => 'banh-kem',        'description' => 'Bánh kem các loại: dâu, socola, tiramisu, matcha...'],
            ['name' => 'Cupcake',         'slug' => 'cupcake',         'description' => 'Cupcake nhỏ xinh, nhiều vị, đóng hộp 6 / 12 chiếc'],
            ['name' => 'Bánh mì',         'slug' => 'banh-mi',         'description' => 'Bánh mì nướng tươi: bánh mì hoa cúc, baguette, bánh mì gối...'],
            ['name' => 'Bánh ngọt',       'slug' => 'banh-ngot',       'description' => 'Bánh ngọt ăn vặt: tart, croissant, donut, eclair...'],
            ['name' => 'Bánh Trung thu',  'slug' => 'banh-trung-thu',  'description' => 'Bánh Trung thu truyền thống & hiện đại, hộp quà sang trọng'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Size mặc định cho bánh kem (3 size)
        $cakeSizes = [
            ['key' => 'small',  'label' => 'Nhỏ (16cm - 4~6 người)',  'price' => 250000],
            ['key' => 'medium', 'label' => 'Vừa (20cm - 6~8 người)',  'price' => 350000],
            ['key' => 'large',  'label' => 'Lớn (24cm - 10~12 người)','price' => 480000],
        ];

        // Size cho cupcake (hộp)
        $cupcakeSizes = [
            ['key' => 'small',  'label' => 'Hộp 6 chiếc',  'price' => 120000],
            ['key' => 'medium', 'label' => 'Hộp 12 chiếc', 'price' => 220000],
        ];

        $products = [
            // === BÁNH SINH NHẬT (custom, lead time 3 ngày) ===
            ['category_id' => 1, 'name' => 'Bánh sinh nhật Hoa hồng kem bơ', 'price' => 350000, 'sale_price' => null, 'stock' => 20, 'featured' => true,  'min_lead_days' => 3, 'size_options' => [['key'=>'small','label'=>'Nhỏ (16cm)','price'=>350000],['key'=>'medium','label'=>'Vừa (20cm)','price'=>480000],['key'=>'large','label'=>'Lớn (24cm)','price'=>650000]], 'description' => 'Bánh kem bơ trang trí hoa hồng thủ công, có thể ghi chữ theo yêu cầu. Vị bánh: vani / socola / dâu. Đặt trước tối thiểu 3 ngày.'],
            ['category_id' => 1, 'name' => 'Bánh sinh nhật trẻ em Unicorn',   'price' => 420000, 'sale_price' => 380000, 'stock' => 15, 'featured' => true,  'min_lead_days' => 3, 'size_options' => [['key'=>'small','label'=>'Nhỏ (16cm)','price'=>380000],['key'=>'medium','label'=>'Vừa (20cm)','price'=>520000],['key'=>'large','label'=>'Lớn (24cm)','price'=>720000]], 'description' => 'Bánh kem hình unicorn dễ thương cho bé, trang trí fondant nhiều màu, vị vani dâu.'],
            ['category_id' => 1, 'name' => 'Bánh sinh nhật 2 tầng sang trọng', 'price' => 850000, 'sale_price' => null, 'stock' => 10, 'featured' => true,  'min_lead_days' => 4, 'size_options' => [['key'=>'medium','label'=>'2 tầng - 12 người','price'=>850000],['key'=>'large','label'=>'2 tầng - 20 người','price'=>1250000]], 'description' => 'Bánh kem 2 tầng phù hợp tiệc lớn, trang trí hoa tươi và topper. Bắt buộc đặt trước 4 ngày.'],

            // === BÁNH KEM (có sẵn / tủ lạnh, lead time 1 ngày) ===
            ['category_id' => 2, 'name' => 'Bánh kem dâu tươi',         'price' => 280000, 'sale_price' => 250000, 'stock' => 30, 'featured' => true,  'min_lead_days' => 1, 'size_options' => $cakeSizes, 'description' => 'Bánh kem tươi phủ kem whipping, topping dâu tây Đà Lạt tươi, cốt bánh vani mềm.'],
            ['category_id' => 2, 'name' => 'Bánh socola Bỉ',            'price' => 320000, 'sale_price' => null,    'stock' => 25, 'featured' => false, 'min_lead_days' => 1, 'size_options' => $cakeSizes, 'description' => 'Cốt bánh socola đậm vị, phủ ganache socola Bỉ 70%, mặn ngọt hài hòa.'],
            ['category_id' => 2, 'name' => 'Bánh tiramisu',             'price' => 290000, 'sale_price' => 260000, 'stock' => 20, 'featured' => true,  'min_lead_days' => 1, 'size_options' => $cakeSizes, 'description' => 'Tiramisu Ý chính hiệu: lớp mascarpone béo ngậy, cốt ladyfinger thấm cafe espresso.'],
            ['category_id' => 2, 'name' => 'Bánh mousse matcha',        'price' => 310000, 'sale_price' => null,    'stock' => 18, 'featured' => false, 'min_lead_days' => 1, 'size_options' => $cakeSizes, 'description' => 'Mousse matcha Nhật mịn mượt, vị trà đậm nhẹ, không quá ngọt, thích hợp người ăn kiêng.'],
            ['category_id' => 2, 'name' => 'Bánh red velvet',           'price' => 300000, 'sale_price' => null,    'stock' => 22, 'featured' => false, 'min_lead_days' => 1, 'size_options' => $cakeSizes, 'description' => 'Red velvet đỏ rực rỡ, vị bánh ẩm ướt, phủ cream cheese frosting kinh điển.'],

            // === CUPCAKE ===
            ['category_id' => 3, 'name' => 'Cupcake vani bơ',           'price' => 25000,  'sale_price' => null,    'stock' => 100,'featured' => false, 'min_lead_days' => 1, 'size_options' => $cupcakeSizes, 'description' => 'Cupcake nhỏ xinh, cốt vani bơ thơm, topping kem bơ nhiều màu pastel.'],
            ['category_id' => 3, 'name' => 'Cupcake socola',            'price' => 28000,  'sale_price' => 25000,   'stock' => 100,'featured' => true,  'min_lead_days' => 1, 'size_options' => $cupcakeSizes, 'description' => 'Cupcake socola đậm vị, topping kem socola sánh mịn, rắc cốm gạo.'],
            ['category_id' => 3, 'name' => 'Cupcake red velvet',        'price' => 30000,  'sale_price' => null,    'stock' => 80, 'featured' => false, 'min_lead_days' => 1, 'size_options' => $cupcakeSizes, 'description' => 'Cupcake red velvet, topping cream cheese trắng mịn, đẹp mắt.'],

            // === BÁNH MÌ ===
            ['category_id' => 4, 'name' => 'Bánh mì hoa cúc Pháp',      'price' => 65000,  'sale_price' => null,    'stock' => 50, 'featured' => true,  'min_lead_days' => 1, 'size_options' => null,         'description' => 'Bánh mì hoa cúc bơ thơm, mềm xốp, ăn sáng hoặc tráng miệng. Trọng lượng 500g/ổ.'],
            ['category_id' => 4, 'name' => 'Baguette Pháp',             'price' => 35000,  'sale_price' => null,    'stock' => 80, 'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Bánh baguette vỏ giòn, ruột dai, dài 50cm, nướng trong ngày.'],
            ['category_id' => 4, 'name' => 'Bánh mì gối sữa',           'price' => 45000,  'sale_price' => 39000,   'stock' => 60, 'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Bánh mì gối sữa mềm xốp, ngọt nhẹ, hộp 8 lát, lý tưởng làm sandwich.'],

            // === BÁNH NGỌT ===
            ['category_id' => 5, 'name' => 'Tart trứng Macao',          'price' => 18000,  'sale_price' => null,    'stock' => 120,'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Tart trứng Macao vỏ giòn, nhân trứng béo ngậy, chuẩn vị truyền thống.'],
            ['category_id' => 5, 'name' => 'Croissant bơ Pháp',         'price' => 28000,  'sale_price' => 25000,   'stock' => 90, 'featured' => true,  'min_lead_days' => 1, 'size_options' => null,         'description' => 'Croissant bơ Pháp nhiều lớp, vỏ giòn rụm, ruột mềm xốp.'],
            ['category_id' => 5, 'name' => 'Donut đường phủ',           'price' => 20000,  'sale_price' => null,    'stock' => 100,'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Donut chiên mềm, phủ đường mịn, có thể chọn glaze socola hoặc dâu.'],
            ['category_id' => 5, 'name' => 'Eclair kem',                'price' => 32000,  'sale_price' => null,    'stock' => 70, 'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Eclair Pháp vỏ choux mềm, nhân kem custard, phủ socola.'],

            // === BÁNH TRUNG THU ===
            ['category_id' => 6, 'name' => 'Bánh Trung thu thập cẩm',   'price' => 95000,  'sale_price' => null,    'stock' => 40, 'featured' => false, 'min_lead_days' => 1, 'size_options' => null,         'description' => 'Bánh Trung thu thập cẩm truyền thống 150g, có lạp xưởng, hạt sen, mứt bí.'],
            ['category_id' => 6, 'name' => 'Hộp quà Trung thu 4 bánh',  'price' => 580000, 'sale_price' => 520000,  'stock' => 25, 'featured' => true,  'min_lead_days' => 2, 'size_options' => null,         'description' => 'Hộp quà Trung thu cao cấp gồm 4 bánh (2 nhân thập cẩm + 2 nhân đậu xanh), hộp gỗ sang trọng kèm trà.'],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            Product::create($product);
        }
    }
}
