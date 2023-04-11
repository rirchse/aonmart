<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Library\Utilities;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;

class BannerController extends Controller
{
    public function index()
    {
        $store = Utilities::getActiveStore();
        if (empty($store)) {
            return back()->with('error', __('Select a store to update banners.'));
        }

        $bannersConstants = Banner::BANNERS;
        $bannerTypes = Banner::TYPES;
        $banners = Banner::getBannersByStore($store);

        return view('dashboard.settings.banner.index', compact('bannersConstants', 'banners', 'bannerTypes'));
    }

    public function update(BannerRequest $request)
    {
        $store = Utilities::getActiveStore();
        if (empty($store)) {
            return back()->with('error', __('Select a store to update banners.'));
        }

        $availableBannerKeys = array_keys(Banner::BANNERS);
        $images = $request->hasFile('image') ? $request->file('image') : [];

        $banners = Banner::getBannersByStore($store);

        $subtitles = $request->input('subtitle');
        foreach ($request->input('title') as $key => $value) {
            if (in_array($key, $availableBannerKeys)) {
                $banner = $banners->where('key', $key)
                    ->first();
                $banner->title = $value;
                $banner->subtitle = $subtitles[$key] ?? null;
                $banner->save();

                if (array_key_exists($key, $images)) {
                    $banner->addMedia($images[$key])->toMediaCollection();
                }
            }
        }
        return back()->with('success', 'Banners updated successfully.');
    }
}
