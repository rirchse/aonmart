<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Requests\SliderRequest;
use App\Library\Utilities;
use App\Models\Banner;
use App\Models\Slider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index(): View
    {
        Utilities::checkPermissions(['settings.slider.create', 'settings.slider.edit', 'settings.slider.delete']);
        $sliders = Slider::where('store_id', request()->get('active_store_id'))
            ->get();
        return view('dashboard.settings.slider.index', compact('sliders'));
    }

    public function create(): View
    {
        Utilities::checkPermissions('settings.slider.create');
        $availableTypes = Slider::TYPES;
        return view('dashboard.settings.slider.create', compact('availableTypes'));
    }

    public function store(SliderRequest $request): RedirectResponse
    {
        Utilities::checkPermissions('settings.slider.create');

        if ($request->hasFile('image')) {
            $filePath = Str::uuid() . '.' . $request->file('image')->extension();
            $filePath = $request->file('image')->storeAs('images/slider_image', $filePath, 'public');
        }

        Slider::create([
                'image' => $filePath ?? '',
                'store_id' => $request->get('active_store_id')
            ] + $request->validated()
        );

        return back()->with('success', 'Slider Created Successfully');
    }

    public function edit(Slider $slider): View
    {
        Utilities::checkPermissions('settings.slider.create');
        $availableTypes = Slider::TYPES;
        return view('dashboard.settings.slider.edit', compact('slider', 'availableTypes'));
    }

    public function update(SliderRequest $request, Slider $slider): RedirectResponse
    {
        Utilities::checkPermissions('settings.slider.edit');

        $filePath = $slider->image;
        if ($request->hasFile('image')) {
            if ($filePath) Storage::delete($filePath);

            $filename = Str::uuid() . '.' . $request->file('image')->extension();
            $filePath = $request->file('image')->storeAs('images/slider_image', $filename, 'public');
        }

        $slider->update([
                'image' => $filePath
            ] + $request->validated()
        );

        return back()->with('success', 'Slide information successfully updated.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        Utilities::checkPermissions('settings.slider.delete');

        if ($slider->image and Storage::exists($slider->image)) Storage::delete($slider->image);
        $slider->delete();

        return redirect()->back()->with('success', 'Slide successfully deleted.!');
    }
}
