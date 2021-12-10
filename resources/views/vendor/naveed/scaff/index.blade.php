<?php
/* @var $table \Naveed\Scaff\Helpers\Table */
/* @var $gen \Naveed\Scaff\Generators\ModelGenerator */

?>
<template>
    <div>
        <piaf-breadcrumb heading="{{$table->title()}}"/>

        <div class="mb-3 mt-2 d-block">
            <b-button variant="empty"
                      class="pt-0 pl-0 d-inline-block d-md-none"
                      v-b-toggle.displayOptions>
                Options <i class="mdi mdi-chevron-down"/>
            </b-button>
            <b-collapse id="displayOptions" class="d-md-block">
                <div class="d-flex justify-content-start align-items-center">
                    <div class="d-flex justify-content-start align-items-center flex-grow-1">
                        <a class="mr-2 view-icon" href
                           apm-tooltip="Reload {{$table->title()}}"
                           tooltip-placement="right"
                           @click.prevent="{{$table->camel()}}.send()">
                            <i class="mdi mdi-sync"></i>
                        </a>
                        <apm-csv-downloader class="mr-3 view-icon"
                                            :data="{{$table->camel()}}.data.data"
                                            file-name="{{$table->camel()}}.csv"
                                            apm-tooltip="Download data as csv"
                                            :fields="csvFields">
                            <i class="mdi mdi-file-download"></i>
                        </apm-csv-downloader>
                        <b-button v-b-modal.create{{$table->studly(true)}}Modal
                                  variant="outline-dark"
                                  size="xs"
                                  class="top-right-button"
                        >Add New {{$table->title(true)}}
                        </b-button>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <apm-pagination-info :resource="{{$table->camel()}}"/>
                    </div>
                </div>
            </b-collapse>
        </div>

        <create-{{$table->slug(true)}}-modal @on-create="{{$table->camel()}}.send()"/>

        <div class="separator mb-5"></div>

        <b-card class="mb-4 table-responsive">
            <table class="table b-table table-hover">
                <thead>
                <tr>
                    <th>
                        <apm-filter
                            field-name="_id"
                            field-label="ID"
                            v-model="{{$table->camel()}}.config.params"
                        />
                    </th>
@foreach($table->fields as $field)
                    <th>
                        <apm-filter
                                field-name="{{$field->name}}"
                                field-label="{{preg_replace('/ Id$/', '', $field->title())}}"
                                v-model="{{$table->camel()}}.config.params"
@if ($field->type === 'boolean')
                                field-type="select"
                                :options="[{k: true, v: 'Yes'}, {k: false, v: 'No'}]"
                                option-value-field="k"
                                option-label-field="v"
@endif
@if ($field->type === 'enum')
                                field-type="select"
                                :options="[{i:'{!! join("'},{i:'", $field->enumValues) !!}'}]"
                                option-value-field="i"
                                option-label-field="i"
@endif
@if ($field->isForeignKey())
                                field-type="select"
                                options-url="/{{\Illuminate\Support\Str::slug($field->getForeignUrl())}}"
                                option-value-field="_id"
                                option-label-field="name"
@endif
                        />
                    </th>
@endforeach
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="{{$table->camel(true)}} in {{$table->camel()}}.data.data" :key="{{$table->camel(true)}}.{{$table->idField}}">
                    <td>{{'{{'}} {{$table->camel(true)}}.{{$table->idField}} }}</td>
    @foreach($table->fields as $field)
                    <td @if(in_array($field->type, ['text', 'longText'])) style="max-width: 15rem" class="text-truncate"
                        :title="{{$table->camel(true)}}.{{$field->name}}"@endif>
                        <apm-editable
                                type="{{$field->type === 'boolean' ? 'checkbox' : $gen->getEditableType($field)}}" @if($field->type === 'boolean') checkbox-switch @endif
                                field="{{$field->name}}"
                                :url="`/{{$table->slug()}}/{{'${'}}{{$table->camel(true)}}.{{$table->idField}}}`"
                                v-model="{{$table->camel(true)}}.{{$field->name}}"
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
                    </td>
    @endforeach
                    <td class="table-actions">
                        <apm-delete-btn
                            :url="`/{{$table->slug()}}/{{'${'}}{{$table->camel(true)}}.{{$table->idField}}}`"
                            @on-success="{{$table->camel()}}.send()"
                        />
@if(in_array('details', config('naveed-scaff.views')))
                        <router-link :to="`/{{$table->slug()}}/{{'${'}}{{$table->camel(true)}}.{{$table->idField}}}`">
                            <i class="mdi mdi-eye"></i>
                        </router-link>
@endif
                    </td>
                </tr>
                <tr v-if="!{{$table->camel()}}.loading && !{{$table->camel()}}.data.data.length">
                    <td colspan="50" class="text-secondary">No records found.</td>
                </tr>
                </tbody>
            </table>
            <apm-pagination :resource="{{$table->camel()}}" class="justify-content-center"/>
            <apm-loader v-if="{{$table->camel()}}.loading">
                <div class="loading"></div>
            </apm-loader>
        </b-card>
    </div>
</template>

<script>
    import {Resource} from "appmakers-vue/services/http-resource-service";
    import Create{{$table->studly(true)}}Modal from "./create";

    export default {
        name: "index",
        components: {Create{{$table->studly(true)}}Modal},
        data() {
            return {
                {{$table->camel()}}: new Resource('/{{$table->slug()}}', 'get', {
                    pagination: true
                }),
                csvFields: [
                    {name: '{{$table->idField}}', label: 'ID'},
@foreach($table->fields as $field)
                    {name: '{{$field->name}}', label: '{{$field->title()}}'},
@endforeach
                ],
            }
        },
        mounted() {
            this.{{$table->camel()}}.send();
        },
        watch: {
            '{{$table->camel()}}.config.params': {
                handler() {
                    this.{{$table->camel()}}.send({delay: 500});
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
</style>
