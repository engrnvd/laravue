<?php
/* @var $records array */
?>
<?= "<?php\n"
?>

use Illuminate\Database\Seeder;

class WidgetsSeeder extends Seeder
{
    public function run()
    {
        DB::table('dashboard_widgets')->truncate();
        DB::table('dashboard_widgets')->insert([
@foreach($records as $record)
            [
@foreach($record as $field => $value)
                '{{$field}}' => '{{$value}}',
@endforeach
            ],
@endforeach
        ]);
    }
}
