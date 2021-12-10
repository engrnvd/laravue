<?php

namespace App;

use App\Notifications\PasswordReset;
use App\Traits\FindRequestTrait;
use App\Traits\MustVerifyEmail;
use App\Traits\SendsResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\User
 *
 * @property string $_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $email_verified_at
 * @property string $phone
 * @property string $password
 * @property string $role_code
 * @property string $role_name
 * @property string $created_at
 * @property string $updated_at
 * @method static \Illuminate\Database\Query\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|User whereRoleCode($value)
 * @method static \Illuminate\Database\Query\Builder|User whereRoleName($value)
 * @mixin  \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use SendsResponse, MustVerifyEmail, Notifiable;
    use FindRequestTrait;
    protected $guarded = ["_id", "created_at", "updated_at"];
    protected $hidden = [
        'password',
        'verification_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public static $bulkEditableFields = ['role_code'];
    public static $findKeys = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'string',
        'phone' => 'string',
        'password' => 'string',
        'role_code' => 'integer',
        'role_name' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected $appends = [
        'img',
    ];

    public function getImgAttribute()
    {
        return httpHost() . '/images/default-dp.jpg';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function validationRules($attributes = null)
    {
        $rules = [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:users,email,{$this->_id}",
            "phone" => "",
            "password" => "required_with:email_verified_at",
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

    public function isSuperUser()
    {
        return $this->role_code == 1;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function fullName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
