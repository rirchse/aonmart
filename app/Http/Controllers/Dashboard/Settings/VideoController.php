<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Library\Utilities;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class VideoController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['settings.video.create', 'settings.video.edit', 'settings.video.delete']);
        $store = Utilities::getActiveStore();
        $videos = Video::when($store, fn ($query) => $query->where('store_id', $store->id))
            ->with('store')
            ->get();
        return view('dashboard.settings.video.index', compact('videos', 'store'));
    }

    public function create(): RedirectResponse|View
    {
        Utilities::checkPermissions('settings.video.create');

        $store = Utilities::getActiveStore();

        return empty($store)
            ? back()->with('error', __('Select a store to add a video'))
            : view('dashboard.settings.video.create');
    }

    public function store(VideoRequest $request): RedirectResponse
    {
        Utilities::checkPermissions('settings.video.create');

        if (!$store = Utilities::getActiveStore()) {
            return back()->with('error', __('Select a store to add a video'));
        }

        if (($youtubeUid = getUidFromYoutubeLink($request->get('link'))) === false) {
            return redirect()->back()->with('error', 'Invalid youtube link');
        }
        $thumbnail = uploadFile($request->file('thumbnail'), 'video/thumbnail');
        Video::create(
            [
                'thumbnail' => $thumbnail,
                'youtube_uid' => $youtubeUid,
                'store_id' => $store->id,
            ] + $request->validated()
        );
        return redirect()->route('settings.video.index')->with('success', 'Youtube video successfully saved.');
    }

    public function edit(Video $video)
    {
        Utilities::checkPermissions('settings.video.edit');
        return view('dashboard.settings.video.edit', compact('video'));
    }

    public function update(VideoRequest $request, Video $video): RedirectResponse
    {
        Utilities::checkPermissions('settings.video.edit');

        if (($youtubeUid = getUidFromYoutubeLink($request->get('link'))) === false) {
            return redirect()->back()->with('error', 'Invalid youtube link');
        }

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail and Storage::exists('public/' . $video->thumbnail)) {
                Storage::delete('public/' . $video->thumbnail);
            }
            $video->thumbnail = uploadFile($request->file('thumbnail'), 'video/thumbnail');
        }

        $video->update(
            [
                'thumbnail' => $video->thumbnail,
                'youtube_uid' => $youtubeUid
            ] + $request->validated()
        );

        return back()->with('success', 'Youtube video successfully updated.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        Utilities::checkPermissions('settings.video.delete');
        if ($video->thumbnail and Storage::exists('public/' . $video->thumbnail)) {
            Storage::delete('public/' . $video->thumbnail);
        }
        $video->delete();
        return back()->with('success', 'Video successfully deleted.');
    }
}
