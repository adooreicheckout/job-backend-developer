<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class importProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'products:import
    {--oid=-1 : import a single product. --oid=all import all products}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import external api products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(" Start execution import .... ");


        if ($this->option('oid') == "all") {
            $url = "https://fakestoreapi.com/products";
        } else {
            $url = "https://fakestoreapi.com/products/" . $this->option('oid');
        }

        try {
            $curlHandle = curl_init();

            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($curlHandle, CURLOPT_URL, $url);

            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

            $curlData = curl_exec($curlHandle);

            curl_close($curlHandle);
            $resultData = json_decode($curlData);

            if ($this->option('oid') == "all") {

                foreach ($resultData as $data) {
                    $product = Product::create([
                        'description' => $data->description,
                        'name' => $data->title,
                        'price' => $data->price,
                        'category' => $data->category,
                        'image_url' => $data->image,
                    ]);
                }
            } else {
                Product::create([
                    'description' => $resultData->description,
                    'name' => $resultData->title,
                    'price' => $resultData->price,
                    'category' => $resultData->category,
                    'image_url' => $resultData->image,
                ]);
            }

            Log::info("Import performed successfully");
        } catch (\Throwable $th) {
            Log::error('Error getting response from fakestoreapi.');
        }

        Log::info("Finished import.");
    }
}
