<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Variants extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_variants';

    /**
     * @var array belongsTo
     */
    public $belongsTo = [
        'product' => ['Betod\Webinao\Models\Products', 'key' => 'product_id']
    ];
    
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
