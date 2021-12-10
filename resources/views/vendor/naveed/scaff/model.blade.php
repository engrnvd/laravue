<?php
/* @var $table \Naveed\Scaff\Helpers\Table */
/* @var $gen \Naveed\Scaff\Generators\ModelGenerator */
?>
<?='<?php
'?>

namespace {{config('naveed-scaff.model-namespace')}};

use App\Traits\FindRequestTrait;
use Illuminate\Support\Arr;
use {{config('naveed-scaff.parent-model-namespace')}};

/**
 * {{config('naveed-scaff.model-namespace')}}\{{$table->studly(true)}}
 *
@if ($table->idField)
 * {{'@'}}property string ${{$table->idField}}
@endif
@foreach ($table->fields as $field)
 * {{'@'}}property string ${{$field->name}}
@endforeach
@if ($table->timestamps)
 * {{'@'}}property string $created_at
 * {{'@'}}property string $updated_at
@endif
@foreach ( $table->fields as $field )
 * {{'@'}}method static \Illuminate\Database\Query\Builder|{{$table->studly(true)}} where{{$field->studly()}}($value)
@endforeach
 * @mixin \Eloquent
 */
class {{$table->studly(true)}} extends BaseModel
{
    use FindRequestTrait;
    protected $guarded = ["{{$table->idField}}"@if ($table->timestamps), "created_at", "updated_at"@endif];
    public static $bulkEditableFields = ['{!! join("', '", $gen->getBulkEditableFields($table)) !!}'];
@if (!$table->timestamps)
    public $timestamps = false;
@endif
    protected static $findKeys = [
@foreach ($table->fields as $field)
        '{{$field->name}}' => '{{in_array($field->type,['boolean','date','integer'])? $field->type : (in_array($field->type,['dateTime','dateTimeTz'])?'date':'string')}}',
@endforeach
@if ($table->timestamps)
        'created_at' => 'date',
        'updated_at' => 'date',
@endif
    ];

    public function validationRules($attributes = null)
    {
        $rules = [
@foreach ($table->fields as $field)
@if($rule = $gen->getValidationRule($field))
            {!! $rule !!}
@endif
@endforeach
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
