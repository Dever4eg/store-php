<?php


use Phinx\Seed\AbstractSeed;
use App\Models\Product;

class ProductSeeder extends AbstractSeed
{

    public function run()
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->title = $faker->productName;
            $product->description = $faker->realText($faker->numberBetween(400,1000));
            $product->price = $faker->numberBetween(100,10000);
            $product->image = $faker->imageUrl($width = 640, $height = 480, 'technics');
            $product->save();
        }
    }
}
