<?php
namespace Betod\Webinao\Models;

use Model;
/**
 * Model
 */
class Products extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_products';
    protected $jsonable = ['property'];
    /**
     * @var array belongsTo
     */

    public $belongsTo = [
        'category' => ['Betod\Webinao\Models\Categories', 'key' => 'category_id'],
        'post' => ['RainLab\Blog\Models\Post', 'key' => 'post_id'],
    ];

    /**
     * @var array hasMany
     */
    public $hasMany = [
        'variant' => ['Betod\Webinao\Models\Variants', 'key' => 'product_id']
    ];
    /**
     * @var array attachOne
     */
    public $attachOne = [
        'image' => 'System\Models\File'
    ];
    /**
     * @var array attachManys
     */
    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public function afterAttach($relationName)
    {
        if ($relationName === 'image' && $this->image) {
            $this->image_url = $this->image->getPath();
            $this->timestamps = false;
            $this->save();
            $this->timestamps = true;
        }
    }


}
