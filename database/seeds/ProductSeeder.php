<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create
        ([
            'product_name' => 'Product',
            'description'  => 'Product Description',
            'section_id'   => 1,
        ]);
    }
}
