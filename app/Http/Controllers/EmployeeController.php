<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        return Employee::findRequested();
    }

    public function store(Request $request)
    {
        $employee = new Employee($request->all());
        $this->validate($request, $employee->validationRules());
        $employee->save();
        return $employee;
    }

    public function show(Request $request, Employee $employee)
    {
        return $employee;
    }

    public function update(Request $request, Employee $employee)
    {
        if ($request->wantsJson()) {
            $this->validateUpdatedRequest($request->name, $request->value, $employee);
            $data = [$request->name => $request->value];
            $employee->update($data);
            return $employee;
        }

        $this->validate($request, $employee->validationRules());
        $employee->update($request->all());
        return $employee;
    }

    public function destroy(Request $request, Employee $employee)
    {
        $employee->delete();
        return "Employee deleted";
    }

    public function bulkDelete(Request $request)
    {
        $items = $request->items;
        if (!$items) {
            abort(403, "Please select some items.");
        }

        if (!$ids = collect($items)->pluck('id')->all()) {
            abort(403, "No ids provided.");
        }

        Employee::whereIn('id', $ids)->delete();
        return response("Deleted");
    }

    public function bulkEdit(Request $request)
    {
        if (!$field = $request->field) {
            abort(403, "Invalid request. Please provide a field.");
        }

        if (!$fieldName = Arr::get($field, 'name')) {
            abort(403, "Invalid request. Please provide a field name.");
        }

        if (!in_array($fieldName, Employee::$bulkEditableFields)) {
            abort(403, "Bulk editing the {$fieldName} is not allowed.");
        }

        if (!$items = $request->items) {
            abort(403, "Please select some items.");
        }

        if (!$ids = collect($items)->pluck('id')->all()) {
            abort(403, "No ids provided.");
        }

        $this->validateUpdatedRequest($fieldName, Arr::get($field, 'value'));

        Employee::whereIn('id', $ids)->update([$fieldName => Arr::get($field, 'value')]);
        return response("Updated");
    }

    protected function validateUpdatedRequest($field, $value, $employee = null)
    {
        if (!$employee) $employee = new Employee();
        $data = [$field => $value];
        $validator = \Validator::make($data, $employee->validationRules($field));
        if ($validator->fails()) {
            abort(403, $validator->errors()->first($field));
        }
    }
}
