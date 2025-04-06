<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Filters extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_filters';
    
    public $belongsTo = [
        'category' => ['Betod\Webinao\Models\Categories', 'key' => 'category_id']
    ];
    protected $jsonable = [
        'options'
    ];
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
