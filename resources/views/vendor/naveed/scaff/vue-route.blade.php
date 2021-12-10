<?php
/* @var $table \Naveed\Scaff\Helpers\Table */
/* @var $gen \Naveed\Scaff\Generators\ModelGenerator */
?>
        {
            path: '{{$table->slug()}}',
            component: () => import(/* webpackChunkName: "{{$table->slug()}}" */ '../views/app/{{$table->slug()}}'),
        },
@if(in_array('details', config('naveed-scaff.views')))
        {
            path: '{{$table->slug()}}/:id',
            component: () => import(/* webpackChunkName: "{{$table->slug()}}-details" */ '../views/app/{{$table->slug()}}/details'),
        },
@endif
