<?php
namespace Betod\Webinao\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Cache;
use Betod\Webinao\Models\Products as Product;


class Products extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Betod.Webinao', 'main-menu-item', 'side-menu-item');
    }
    public function update($recordId, $context = null)
    {
        // Xóa cache trước khi gọi hàm update mặc định
        $product = Product::find($recordId);
        if ($product) {
            $cacheDuration = 60; // Thời gian cache tính bằng phút
            $cacheKey = 'product_' . $product->slug;
            Cache::put($cacheKey, $product->load(['category.parent', 'variant', 'post', 'image', 'gallery']), $cacheDuration);
        }

        // Gọi hàm update mặc định của OctoberCMS
        return $this->asExtension('FormController')->update($recordId, $context);
    }
}
