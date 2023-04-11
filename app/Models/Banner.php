<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const TYPE_BANNER = 'banner';
    const TYPE_WHY_PEOPLE_LOVE = 'why_people_love';

    const BANNER_TOP = 'banner_top';
    const SUB_BANNER_ONE = 'sub_banner_one';
    const SUB_BANNER_TWO = 'sub_banner_two';
    const WHY_PEOPLE_LOVE_ONE = 'why_people_love_one';
    const WHY_PEOPLE_LOVE_TWO = 'why_people_love_two';
    const WHY_PEOPLE_LOVE_THREE = 'why_people_love_three';

    const BANNERS = [
        self::BANNER_TOP => 'Banner Top',
        self::SUB_BANNER_ONE => 'Sub Banner One',
        self::SUB_BANNER_TWO => 'Sub Banner Two',
        self::WHY_PEOPLE_LOVE_ONE => 'Why People Love One',
        self::WHY_PEOPLE_LOVE_TWO => 'Why People Love Two',
        self::WHY_PEOPLE_LOVE_THREE => 'Why People Love Three',
    ];

    const TYPES = [
        self::TYPE_BANNER => 'Banner',
        self::TYPE_WHY_PEOPLE_LOVE => 'Why People Love Banner'
    ];

    protected $fillable = [
        'store_id', 'key', 'title', 'status'
    ];
    protected $with = ['media'];

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->singleFile()
            ->useFallbackUrl(asset('/default/default_image.png'))
            ->useFallbackPath('/default/default_image.png');
    }

    public static function insertDefaultBannersForStore(Store $store)
    {
        $banners = [
            [
                'store_id' => $store->id,
                'key' => Banner::BANNER_TOP,
                'title' => Banner::BANNERS[Banner::BANNER_TOP],
                'type' => Banner::TYPE_BANNER
            ],
            [
                'store_id' => $store->id,
                'key' => Banner::SUB_BANNER_ONE,
                'title' => Banner::BANNERS[Banner::SUB_BANNER_ONE],
                'type' => Banner::TYPE_BANNER
            ],
            [
                'store_id' => $store->id,
                'key' => Banner::SUB_BANNER_TWO,
                'title' => Banner::BANNERS[Banner::SUB_BANNER_TWO],
                'type' => Banner::TYPE_BANNER
            ],
            [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_ONE,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_ONE],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ],
            [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_TWO,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_TWO],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ],
            [
                'store_id' => $store->id,
                'key' => Banner::WHY_PEOPLE_LOVE_THREE,
                'title' => Banner::BANNERS[Banner::WHY_PEOPLE_LOVE_THREE],
                'type' => Banner::TYPE_WHY_PEOPLE_LOVE
            ]
        ];

        Banner::insert($banners);
    }

    public static function getBannersByStore(Store $store): Collection
    {
        return self::where('store_id', $store->id)
            ->get();
    }
}
