<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * App\BaseModel
 *
 * @property string $_id
 * @mixin  \Eloquent
 */
class BaseModel extends Model
{
    public static function boot()
    {
        static::creating(function ($model) {
            $model->_id = $model->getNextId();
            return true;
        });

        parent::boot();
    }

    public function getNextId()
    {
        $collection = $this->getTable();
        if (!$doc = \DB::collection('id_counters')->find($collection)) {
            \DB::collection('id_counters')->insert(['_id' => $collection, 'sequence' => 1]);
        }

        $document = \DB::getCollection('id_counters')->findOneAndUpdate(
            ['_id' => $collection],
            ['$inc' => ['sequence' => 1]],
            ['new' => true, 'upsert' => true]
        );
        return $document->sequence;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->findOrFail(intval($value));
    }
}
