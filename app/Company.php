<?php

namespace App;

use App\Traits\FindRequestTrait;
use Illuminate\Support\Arr;
use App\BaseModel;

/**
 * App\Company
 *
 * @property string $_id
 * @property string $name
 * @property string $email
 * @property string $logo
 * @property string $website
 * @property string $created_at
 * @property string $updated_at
 * @method static \Illuminate\Database\Query\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Company whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Company whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|Company whereWebsite($value)
 * @mixin  \Eloquent
 */
class Company extends BaseModel
{
    use FindRequestTrait;
    protected $guarded = ["_id", "created_at", "updated_at"];
    public static $bulkEditableFields = ['name', 'email', 'logo', 'website'];
    protected static $findKeys = [
        'name' => 'string',
        'email' => 'string',
        'logo' => 'string',
        'website' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function validationRules($attributes = null)
    {
        $rules = [
            "name" => "required",
            "email" => "required|email",
            "logo" => "",
            "website" => "",
        ];

        // no list is provided
        if (!$attributes)
            return $rules;

        // a single attribute is provided
        if (!is_array($attributes))
            return [$attributes => Arr::get($rules, $attributes, '')];

        // a list of attributes is provided
        $newRules = [];
        foreach ($attributes as $attr)
            $newRules[$attr] = Arr::get($rules, $attr, '');
        return $newRules;
    }

}
