<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueCI implements Rule
{
    private $table;
    private $ignoreId;
    private $func;

    public function __construct($table, $ignoreId = null, $func = null)
    {
        $this->table = $table;
        $this->ignoreId = $ignoreId;
        $this->func = $func;
    }

    public function passes($attribute, $value)
    {
        $query = \DB::table($this->table)->where($attribute, 'like', $value);
        if ($this->ignoreId) $query->where('_id', '<>', $this->ignoreId);
        if ($this->func) $query = call_user_func($this->func, $query);
        return $query->count() === 0;
    }

    public function message()
    {
        return 'The :attribute is already taken.';
    }
}
