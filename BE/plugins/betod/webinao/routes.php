<?php
use Betod\Webinao\Models\Products as Products;
use Betod\Webinao\Models\Orders as Orders;
use Betod\Webinao\Models\Categories as Categories;
use Betod\Webinao\Models\Filters as Filters;
use Betod\Webinao\Controllers\PayPalController;
use Illuminate\Support\Facades\Cache;

Route::group(['prefix' => 'apiProduct'], function () {
    Route::get('product/{slug}', function ($slug) {

        $cacheDuration = 60;
        $cacheKey = 'product_' . $slug;
        $product = Cache::remember($cacheKey, $cacheDuration, function () use ($slug) {
            return Products::with(['category.parent', 'category.front_image', 'category.back_image', 'variant', 'post', 'image', 'gallery', 'front_template', 'back_template'])->where('slug', $slug)->first();
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
Route::group(['prefix' => 'apiImage'], function () {
    Route::get('/image-proxy/{path}', function ($path) {
        $fullPath = storage_path('app/uploads/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => mime_content_type($fullPath),
        ]);
    })->where('path', '.*');
});

Route::group(['prefix' => 'apiOrder'], function () {
    Route::post('createOrder', 'Betod\Webinao\Controllers\OrderController@createOrder');

    Route::get('order/{order_code}', function ($order_code) {
        $cacheDuration = 60;
        $cacheKey = 'order_' . $order_code;
        $data = Cache::remember($cacheKey, $cacheDuration, function () use ($order_code) {
            return Orders::with('orderdetail.product')->where('order_code', $order_code)->first();
        });
        if (!$data) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return $data;
    });
});

Route::group(['prefix' => 'apiPaypal'], function () {
    // Route để tạo đơn hàng
    Route::post('createOrder', [PayPalController::class, 'createOrder']);

    // Route để xác nhận thanh toán
    Route::post('captureOrder', [PayPalController::class, 'captureOrder']);
});

Route::group(['prefix' => 'apiCategory'], function () {
    Route::get('category/{slug}', function ($slug) {
        $cacheDuration = 60;
        $cacheKey = 'category_' . $slug;
        $data = Cache::remember($cacheKey, $cacheDuration, function () use ($slug) {
            $category = Categories::with(['parent'])->where('slug', $slug)->first();
            $products = Products::with(['category.parent', 'image', 'variant'])->where('category_id', $category->id)->get();
            ;
            return [
                'category' => $category->toArray(),
                'products' => $products->toArray(),
            ];
        });
        if (!$data) {
            return response()->json(['message' => 'Không tìm thấy danh mục'], 404);
        }
        return response()->json($data);
    });
    Route::get('filter/{slug}', function ($slug) {
        $cacheDuration = 60;
        $cacheKey = 'filter_' . $slug;
        $data = Cache::remember($cacheKey, $cacheDuration, function () use ($slug) {
            $category = Categories::with(['parent'])->where('slug', $slug)->first();
            $filters = Filters::with([])->where('category_id', $category->id)->get();

            return $filters;
        });
        if (!$data) {
            return response()->json(['message' => 'Không tìm thấy bộ lọc'], 404);
        }
        return response()->json($data);
    });
});
