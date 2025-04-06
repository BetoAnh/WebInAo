<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class OrderDetail extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_order_positions';

    public $jsonable = ['variant'];
    public $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'variant',
        'frontImage',
        'backImage',
    ];

    public $belongsTo = [
        'order' => ['Betod\Webinao\Models\Orders', 'key' => 'order_id'],
        'product' => ['Betod\Webinao\Models\Products', 'key' => 'product_id'],
    ];

    public $attachOne = [
        'frontImage' => 'System\Models\File',
        'backImage' => 'System\Models\File',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($orderDetail) {
            try {
                if ($orderDetail->frontImage) {
                    $orderDetail->frontImage->delete();
                }
                if ($orderDetail->backImage) {
                    $orderDetail->backImage->delete();
                }
            } catch (\Exception $e) {
                \Log::error('Error deleting image: ' . $e->getMessage());
            }
        });
    }
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
