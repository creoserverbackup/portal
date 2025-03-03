<?php

namespace App\Console\Commands;

use App\Models\CatalogCategory;
use App\Services\MeilisearchSetting;
use Illuminate\Console\Command;

class DoAny extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:any';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do something';

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

//       $result = \DB::connection('sphinx')->select('select * from catalog_product');
//       dump($result);

//        $query = \App\Models\CatalogProduct::search('dell')->updateSynonyms();

        /** @var MeilisearchSetting $settings */
        $settings = app(MeilisearchSetting::class);

//        $settings->updateSynonyms([
//            ',' => ['.'],
//            '.' => [',']
//        ]);
//        $query = 'hi';
//        dump($query);

        $settings->updateSortableAttributes(['price','sold','quantity','rating']);


//        $result = preg_replace('/(\d+),(\d+)/',  '$1.$2','146GB SAS 15K 6Gbps 2,5"');
//        dump($result);


        return 0;
    }
}
