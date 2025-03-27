<?php
use Betod\Webinao\Models\Products as Products;
use Illuminate\Support\Facades\Cache;

Route::group(['prefix' => 'apiProduct'], function () {
    Route::get('product/{slug}', function ($slug) {

        $cacheDuration = 60;
        $cacheKey = 'product_' . $slug;
        $product = Cache::remember($cacheKey, $cacheDuration, function () use ($slug) {
            return Products::with(['category.parent', 'category.front_image', 'category.back_image', 'variant', 'post', 'image', 'gallery'])->where('slug', $slug)->first();
        });
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    });

    Route::get('category/{slug}/{childrenSlug?}', function ($slug, $childrenSlug = null) {
        $cacheDuration = 60;
        if ($childrenSlug) {
            $cacheKey = 'products_' . $slug . '_' . $childrenSlug;
            $products = Cache::remember($cacheKey, $cacheDuration, function () use ($childrenSlug) {
                return Products::with(['category', 'variant'])
                    ->whereHas('category', function ($query) use ($childrenSlug) {
                        $query->where('slug', $childrenSlug);
                    })
                    ->get();
            });
        } else {
            $cacheKey = 'products_' . $slug;
            $products = Cache::remember($cacheKey, $cacheDuration, function () use ($slug) {
                $productsParent = Products::with(['category', 'variant'])
                    ->whereHas('category', function ($query) use ($slug) {
                        $query->where('slug', $slug);
                    })
                    ->get();

                $productsChildren = Products::with(['category', 'variant'])
                    ->whereHas('category.parent', function ($query) use ($slug) {
                        $query->where('slug', $slug);
                    })
                    ->get();

                return $productsParent->merge($productsChildren);
            });
        }
        if (!$products) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($products);
    });

});

