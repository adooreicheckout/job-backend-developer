<?php

namespace App\Console\Commands\FakeData;

use App\Models\Product;
use Illuminate\Console\Command;

class FakeDataProducts extends Command
{
    protected $signature = 'fakedata:products
    {number : Number of products to create}';

    protected $description = 'Create fake products';

    public function handle(): int
    {
        $number = round($this->argument('number') ?: 1);

        Product::factory()->count($number)->create();
        $this->info("Finished creating {$number} fake products.");
        return 0;
    }
}
