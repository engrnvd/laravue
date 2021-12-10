<?php
/* @var $table \Naveed\Scaff\Helpers\Table */
/* @var $gen \Naveed\Scaff\Generators\ModelGenerator */
?>

<template>
    <div class="{{$table->slug()}}-detail-page">
        <b-row>
            <b-colxx xxs="12">
                <h1>{{'{{'}} req.data._id }}</h1>
                <piaf-breadcrumb/>

                <b-row>
                    <b-colxx xxs="12" lg="4" class="mb-4">
                        <b-card class="mb-4" no-body>
                            <b-card-body>
@foreach($table->fields as $field)
                                    <p class="text-muted text-small mb-2">{{preg_replace('/ Id$/', '', $field->title())}}</p>
                                    <p class="mb-3">
                                        <apm-editable
                                            type="{{$field->type === 'boolean' ? 'checkbox' : $gen->getEditableType($field)}}" @if($field->type === 'boolean') checkbox-switch @endif
                                            field="{{$field->name}}"
                                            :url="`/{{$table->slug()}}/{{'${'}}req.data.{{$table->idField}}}`"
                                            v-model="req.data.{{$field->name}}"
@if ($field->isForeignKey())
                                            dd-options-url="/{{\Illuminate\Support\Str::slug($field->getForeignUrl())}}"
                                            dd-label-field="name"
                                            dd-value-field="{{$table->idField}}"
@endif
@if ($field->type === 'enum')
                                            :dd-options="[{i:'{!! join("'},{i:'", $field->enumValues) !!}'}]"
                                            dd-label-field="i"
                                            dd-value-field="i"
@endif
                                        ></apm-editable>
                                    </p>
                                @endforeach
                            </b-card-body>
                        </b-card>
                    </b-colxx>
                    <b-colxx xxs="12" lg="8">
                        <b-card class="mb-4" title="More details">
                            <table class="table b-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </b-card>
                    </b-colxx>
                </b-row>
            </b-colxx>
        </b-row>
        <div class="loading" v-if="req.loading"></div>
    </div>
</template>

<script>
    import {Resource} from 'appmakers-vue/services/http-resource-service';

    export default {
        name: "{{$table->slug()}}-details",
        data() {
            return {
                req: new Resource(`/{{$table->slug()}}/${this.$route.params.id}`),
            }
        },
        methods: {},
        computed: {},
        created() {
            this.req.send();
        }
    }
</script>

<style scoped lang="scss">

</style>


