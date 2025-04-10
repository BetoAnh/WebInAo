<?php
namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Revenue extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_additional_user';
    protected $fillable = ['sale_date', 'total_revenue'];
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
