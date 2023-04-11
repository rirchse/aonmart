<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Store;
use Throwable;
use App\Models\Banner;
use Artisan;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class ScriptCommand extends Command
{
    protected $signature = 'script:execute';

    protected $description = 'Execute dummy script.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $scripts = DB::table('script_manager')
                ->where('is_executed', 0)
                ->get();

            foreach ($scripts as $script) {
                $methodName = $script->name;
                $this->info('Started script: ' . $methodName);

                if (method_exists($this, $methodName)) {
                    $this->$methodName();
                    $script->is_executed = true;
                    $script->status = 'Success';
                } else {
                    $script->status = 'Failed - Script method not found';
                }

                $script->execution_count += 1;

                DB::table('script_manager')
                    ->where('id', $script->id)
                    ->update([
                        'is_executed' => $script->is_executed,
                        'status' => $script->status,
                        'execution_count' => $script->execution_count,
                    ]);
                $this->info('Ended script: ' . $methodName);
            }
            return 1;
        } catch (Throwable $throwable) {
            return 0;
        }
    }

    private function updateRolePermissions(): void
    {
        $permissions = Permission::where('name', 'like', 'sell.%')
            ->get();
        foreach ($permissions as $permission) {
            $permission->name = str_replace('sell', 'sale', $permission->name);
            $permission->save();
        }
    }

    private function insertInitialBanners()
    {
        Schema::dropIfExists('banners');
        $this->info('Dropped banners table.');

        DB::table('migrations')
            ->where('migration', '2021_01_24_112938_create_banners_table')
            ->delete();
        $this->info('Deleted migration.');

        Artisan::call('migrate');
        $this->info('Migrated banners table.');

        Banner::insert([
            [
                'key' => 'banner_one',
                'title' => 'Banner One',
                'type' => Banner::TYPE_BANNER
            ],
            [
                'key' => 'banner_two',
                'title' => 'Banner Two',
                'type' => Banner::TYPE_BANNER
            ]
        ]);
        $this->info('Inserted initial banners.');

        if (Permission::where('name', 'settings.banner.show')->first()) {
            $this->info('Permission already exists.(settings.banner.show)');
        } else {
            Permission::create(['name' => 'settings.banner.show']);
            $this->info('Created permission.(settings.banner.show)');
        }

        if (Permission::where('name', 'settings.banner.edit')->first()) {
            $this->info('Permission already exists.(settings.banner.edit)');
        } else {
            Permission::create(['name' => 'settings.banner.edit']);
            $this->info('Created permission.(settings.banner.edit)');
        }
    }

    private function updateProductDataFromCSV()
    {
        $file = public_path('extra/aonmart-product-data.csv');

        if (! file_exists($file)) {
            $this->info('File not found.');
            die();
        }

        $file = fopen($file, 'r');
        $i = 0;
        $totalSuccess = 0;
        $totalRows = 0;
        while (($line = fgetcsv($file)) !== false) {
            if (! $line[0]) {
                continue;
            }
            if ($i == 0) {
                $i++;
                continue;
            }
            $product_id = $line[0];
            $bar_code = $line[1];
            $regular_price = $line[2];
            $product_name = $line[3];
            $product = Product::find($product_id);
            if ($product) {
                $product->name = $product_name;
                $product->barcode = $bar_code;
                $product->regular_price = $regular_price;
                $product->save();
                $totalSuccess++;
            }
            $totalRows++;
        }
        $this->info('Total rows: ' . $totalRows);
        $this->info('Total success: ' . $totalSuccess);
        $this->info('Total failed: ' . ($totalRows - $totalSuccess));
    }

    private function reportUpdateMigration()
    {
        Permission::insert([
            ['name' => 'report.supplier'],
            ['name' => 'report.customer'],
            ['name' => 'report.purchase'],
            ['name' => 'report.stock']
        ]);
    }

    private function insertWhyPeopleLoverBanners()
    {
        Artisan::call('migrate');
        $this->info('Migrated banners table.');

        $stores = Store::all();

        $newBanners = [];
        $stores->each(function ($store) use (&$newBanners) {
            $newBanners[] = [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_ONE,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_ONE],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ];
            $newBanners[] = [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_TWO,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_TWO],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ];
            $newBanners[] = [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_THREE,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_THREE],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ];
        });
        Banner::insert($newBanners);
        $this->info('Inserted why people love banners.');
    }
}
