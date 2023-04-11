<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Library\Utilities;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FeatureProducts;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Subcategory;
use App\Models\SubSubcategory;
use App\Models\Tag;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        Utilities::checkPermissions(['product.all', 'product.add', 'product.view', 'product.edit', 'product.delete']);
        $store = Utilities::getActiveStore();
        $products = Product::with('categories')->when($store, function ($query) use ($store) {
            return $query->whereHas('stores', function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            });
        })->latest()->get();
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        Utilities::checkPermissions(['product.all', 'product.add']);

        $categories = Category::whereStatus(1)
            ->orderBy('name')
            ->get(['id', 'name']);
        $features = Feature::whereStatus(1)->get(['id', 'name']);
        $tags = Tag::whereStatus(1)->get(['id', 'name']);
        $barcode = date('ymd') . intval(rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9));
        $barcode = ean13_with_check_digit($barcode);
        $units = Unit::whereStatus(1)->get(['id', 'name']);

        return view('admin.product.create', compact('categories', 'tags', 'features', 'barcode', 'units'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Utilities::checkPermissions(['product.all', 'product.add']);

        $filePath = null;
        $photoGallery = null;
        if ($request->hasFile('image')) {
            $filePath = Rand() . '.' . $request->image->getClientOriginalExtension();
            $filePath = $request->image->storeAs('images/product_img', $filePath, 'public');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryFile = Rand() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryFile = $galleryImage->storeAs('images/product_img', $galleryFile, 'public');
                $photoGallery .= $galleryFile . ',';
            }
        }

        if ($request->input('unit_id')) {
            if (!Unit::find($request->input('unit_id'))) {
                $unit = Unit::create([
                    'name' => $request->input('unit_id')
                ]);
                $request->unit_id = $unit->id;
            }
        }

        $initialStock = 0;
        if ($request->input('stock')) {
            $initialStock = $request->input('stock');
        }

        $product = Product::create([
            'name' => $request->input('name'),
            'image' => $filePath,
            'gallery' => rtrim($photoGallery, ','),
            'stock' => $initialStock,
            'quantity' => $request->input('quantity'),
            'unit_id' => $request->unit_id,
            'regular_price' => $request->input('regular_price'),
            'sell_price' => $request->input('sell_price'),
            'discount' => $request->input('discount'),
            'short_description' => $request->input('short_description'),
            'full_description' => $request->input('full_description'),
            'barcode' => $request->input('barcode'),
        ]);

        if ($request->input('category_id')) {
            foreach ($request->input('category_id') as $category) {
                $product->categories()->attach($category);
            }
        }

        if ($request->input('subcategory_id')) {
            foreach ($request->input('subcategory_id') as $subcategory) {
                $product->subcategories()->attach($subcategory);
            }
        }

        if ($request->input('sub_subcategory_id')) {
            foreach ($request->input('sub_subcategory_id') as $subSubcategory) {
                $product->subSubcategories()->attach($subSubcategory);
            }
        }

        // dynamic tag in product
        $tags = [];
        if ($request->input('tag_id')) {
            foreach ($request->input('tag_id') as $i => $tag) {
                if (Tag::find($tag)) {
                    $tags[$i] = $tag;
                } else {
                    $newTag = Tag::create([
                        'name' => $tag,
                    ]);
                    $tags[$i] = $newTag->id;
                }
            }
        }
        if ($tags) {
            foreach ($tags as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id' => $tag,
                ]);
            }
        }

        if ($request->input('features')) {
            foreach ($request->input('features') as $feature) {
                FeatureProducts::create([
                    'feature_id' => $feature,
                    'product_id' => $product->id,
                ]);
            }
        }

        return back()->with('success', 'Product Saved Successfully.');
    }

    public function show($id)
    {
        //
    }


    public function edit(Product $product)
    {
        Utilities::checkPermissions(['product.all', 'product.edit']);

        $data['product'] = $product;
        $data['categories'] = Category::where('status', 1)->get(['id', 'name']);
        $data['oldCategories'] = $product->categories->pluck('id');
        $data['subcategories'] = Subcategory::whereStatus(1)->whereIn('category_id', $data['oldCategories'])->get(['id', 'name']);
        $data['oldSubcategories'] = $product->Subcategories->pluck('id');
        $data['subSubcategories'] = SubSubcategory::whereStatus(1)->whereIn('subcategory_id', $data['oldSubcategories'])->get(['id', 'name']);
        $data['oldSubSubcategories'] = $product->SubSubcategories->pluck('id');
        $data['tags'] = Tag::where('status', 1)->get(['id', 'name']);
        $data['oldTags'] = ProductTag::where('product_id', $product->id)->pluck('tag_id');
        $data['features'] = Feature::where('status', 1)->get(['id', 'name']);
        $data['oldFeatures'] = FeatureProducts::where('product_id', $product->id)->pluck('feature_id');
        $data['units'] = Unit::whereStatus(1)->get(['id', 'name']);
        $data['oldUnits'] = $product->unit()->pluck('id');
        $data['old_gallery'] = collect(explode(',', $product->gallery));

        return view('admin.product.edit', $data);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        Utilities::checkPermissions(['product.all', 'product.edit']);

        $filePath = $product->image;
        if ($request->hasFile('image')) {
            if (File::exists('storage/' . $filePath)) {
                File::delete('storage/' . $filePath);
            }
            $filePath = Rand() . '.' . $request->image->getClientOriginalExtension();
            $filePath = $request->image->storeAs('images/product_img', $filePath, 'public');
        }

        $photoGallery = $product->gallery;
        if ($request->hasFile('gallery')) {
            $old_gallery_array = explode(',', $product->gallery);
            foreach ($request->file('gallery') as $key => $galleryImage) {
                $galleryFile = Rand() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryFile = $galleryImage->storeAs('images/product_img', $galleryFile, 'public');
                $old_gallery_array[$key] = $galleryFile;
                $photoGallery = implode(',', $old_gallery_array);
            }
        }

        // dynamic tags in productTags table for one to many
        $oldTags = ProductTag::where('product_id', $product->id)->pluck('tag_id')->toArray();
        $newTags = $request->input('tag_id') ?? [];

        if ($oldTags) {
            $inputTags = $newTags;
            $newTags = array_diff($inputTags, $oldTags);
            $oldDeletes = array_diff($oldTags, $inputTags);
            ProductTag::whereIn('tag_id', $oldDeletes)->delete();
        }

        if ($newTags) {
            foreach ($newTags as $tag) {
                if (!Tag::find($tag)) {
                    $newTag = Tag::create([
                        'name' => $tag,
                    ]);
                    $tag = $newTag->id;
                }
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id' => $tag,
                ]);
            }
        }

        // dynamic features in FeatureProducts table for one to many
        $oldFeatures = FeatureProducts::where('product_id', $product->id)->pluck('feature_id')->toArray();
        $newFeatures = $request->input('features') ?? [];

        if ($oldFeatures) {
            $inputFeatures = $newFeatures;
            $newFeatures = array_diff($inputFeatures, $oldFeatures);
            $oldDeletes = array_diff($oldFeatures, $inputFeatures);
            FeatureProducts::whereIn('feature_id', $oldDeletes)->delete();
        }

        if ($newFeatures) {
            foreach ($newFeatures as $feature) {
                FeatureProducts::create([
                    'feature_id' => $feature,
                    'product_id' => $product->id,
                ]);
            }
        }

        $product->categories()->sync($request->input('category_id'));
        $product->subcategories()->sync($request->input('subcategory_id'));
        $product->subSubcategories()->sync($request->input('sub_subcategory_id'));

        if ($request->input('unit_id')) {
            if (!Unit::find($request->input('unit_id'))) {
                $unit = Unit::create([
                    'name' => $request->input('unit_id')
                ]);
                $request->unit_id = $unit->id;
            }
        }

        $product->update([
            'image' => $filePath,
            'gallery' => $photoGallery,
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            'unit_id' => $request->unit_id,
            'regular_price' => $request->input('regular_price'),
            'sell_price' => $request->input('sell_price'),
            'discount' => $request->input('discount'),
            'short_description' => $request->input('short_description'),
            'full_description' => $request->input('full_description'),
            'barcode' => $request->input('barcode'),
        ]);

        return back()->with('success', 'Update Successful.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Utilities::checkPermissions(['product.all', 'product.delete']);
        $filePath = 'storage/' . $product->image;
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $product->loadCount('purchases');

        if ($product->purchases_count > 0) {
            return back()->with('error', 'This product is purchased in the system');
        }

        $product->delete();

        return back()->with('success', 'Delete Successfully.');
    }

    //ajax subcategory request
    public function categorySubcategory(Request $request)
    {
        if (is_array($request->category_id)) {
            $subCats = Subcategory::whereStatus(1)->whereIn('category_id', $request->category_id)->orderBy('name')->get();
        } else {
            $subCats = Subcategory::whereStatus(1)->where('category_id', $request->category_id)->orderBy('name')->get();
        }

        foreach ($subCats as $sub) {
            echo "<option value='" . $sub->id . "'>" . $sub->name . '</option>';
        }
    }

    //ajax subSubcategory request
    public function subcategorySubSubcategory(Request $request)
    {
        if (is_array($request->subcategory_id)) {
            $subCats = SubSubcategory::whereStatus(1)->whereIn('subcategory_id', $request->subcategory_id)->orderBy('name')->get();
        } else {
            $subCats = SubSubcategory::whereStatus(1)->where('subcategory_id', $request->subcategory_id)->orderBy('name')->get();
        }

        foreach ($subCats as $sub) {
            echo "<option value='" . $sub->id . "'>" . $sub->name . '</option>';
        }
    }

    // Scan barcode and add product to card sale
    public function getBarcodeScannedProduct(Request $request): JsonResponse
    {
        $store = Utilities::getActiveStore();
        $product = $store->products()
            ->where('barcode', $request->code)
            ->with('unit:id,name')
            ->get();
        return response()->json([
            'product' => $product,
        ]);
    }

    public function product_barcode_print_list()
    {
        $store = Utilities::getActiveStore();
        $products = Product::with('categories')->when($store, function ($query) use ($store) {
            return $query->whereHas('stores', function ($query) use ($store) {
                return $query->where('store_id', $store->id);
            });
        })->latest()->get();
        return view('admin.product.barcode_print_list', compact('products'));
    }

    public function product_barcode_print(Request $request)
    {
        $products = $request->input('products') ?? [];
        $quantities = $request->input('qty') ?? [];
        return view('admin.product.barcode_print', compact('products', 'quantities'));
    }
}
