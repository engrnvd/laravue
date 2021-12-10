<?php

namespace App;

use App\Traits\FindRequestTrait;
use Illuminate\Support\Arr;
use App\BaseModel;

/**
 * App\Employee
 *
 * @property string $_id
 * @property string $first_name
 * @property string $last_name
 * @property string $company_id
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 * @method static \Illuminate\Database\Query\Builder|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|Employee whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|Employee whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|Employee whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Employee wherePhone($value)
 * @mixin  \Eloquent
 */
class Employee extends BaseModel
{
    use FindRequestTrait;
    protected $guarded = ["_id", "created_at", "updated_at"];
    public static $bulkEditableFields = ['first_name', 'last_name', 'company_id', 'email', 'phone'];
    protected static $findKeys = [
        'first_name' => 'string',
        'last_name' => 'string',
        'company_id' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function validationRules($attributes = null)
    {
        $rules = [
            "first_name" => "required",
            "last_name" => "required",
            "company_id" => "required",
            "email" => "email",
            "phone" => "",
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
