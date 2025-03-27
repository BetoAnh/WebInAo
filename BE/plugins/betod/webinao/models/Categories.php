<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Categories extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_categories';
    /**
     * @var array belongsTo
     */
    public $hasMany = [
        'children' => [self::class, 'key' => 'parent_id']
    ];
    /**
     * @var array belongsTo
     */

    public $belongsTo = [
        'parent' => ['Betod\Webinao\Models\Categories', 'key' => 'parent_id']
    ];

    public $attachOne = [
        'front_image' => 'System\Models\File',
        'back_image' => 'System\Models\File'
    ];
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
