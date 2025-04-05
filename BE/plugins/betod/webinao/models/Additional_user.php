<?php namespace Betod\Webinao\Models;

use Model;

/**
 * Model
 */
class Additional_user extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'betod_webinao_additional_user';
    protected $fillable = ['user_id', 'phone', 'province', 'district', 'subdistrict', 'address'];
    public $belongsTo = [
        'user' => 'RainLab\User\Models\User'
    ];
    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
