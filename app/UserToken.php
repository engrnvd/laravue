<?php

namespace App;

use App\BaseModel;

/**
 * App\UserToken
 *
 * @property string $_id
 * @property string $user_id
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 * @mixin  \Eloquent
 */
class UserToken extends BaseModel
{
    protected $guarded = ["_id", "created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}
