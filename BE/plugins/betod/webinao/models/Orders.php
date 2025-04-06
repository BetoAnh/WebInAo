<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Orders extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_orders';
    public $jsonable = ['property'];
    protected $fillable = [
        'user_id',
        'property',
        'order_code',
        'price',
    ];

    public $hasMany = [
        'orderdetail' => ['Betod\Webinao\Models\OrderDetail', 'key' => 'order_id'],
    ];
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($order) {
            // Xóa tất cả orderdetails liên quan và file đính kèm
            foreach ($order->orderdetail as $orderDetail) {
                if ($orderDetail->frontImage) {
                    $orderDetail->frontImage->delete();
                }
                if ($orderDetail->backImage) {
                    $orderDetail->backImage->delete();
                }

                // Xóa orderdetail
                $orderDetail->delete();
            }
        });
    }

    public function getStateLabelAttribute()
    {
        $statuses = [
            0 => 'Chưa chuyển',
            1 => 'Đang vận chuyển',
            2 => 'Đã giao'
        ];

        return $statuses[$this->state_id] ?? 'Không xác định';
    }

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
