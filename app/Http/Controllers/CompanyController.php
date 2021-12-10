<?php

namespace App\Http\Controllers;

use App\Company;
use App\Helpers\ImgHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function changePic(Request $request, Company $company)
    {
        $path = public_path($company->logo);
        $saved = ImgHelper::base64ToImg($request->img_data, $path);
        if (!$saved) {
            abort(400, "Could not save image please try again later.");
        }
        return "Saved";
    }

    public function index(Request $request)
    {
        return Company::findRequested();
    }

    public function store(Request $request)
    {
        $company = new Company($request->all());
        $this->validate($request, $company->validationRules());

        $file = $request->file('attachments');
        if (!$file) abort(400, "No image file selected");
        $newName = Str::random(8) . time() . "." . $file->getClientOriginalExtension();
        $newPath = storage_path('app/public');
        $file->move($newPath, $newName);
        $company->logo = "/storage/$newName";

        $company->save();
        return $company;
    }

    public function show(Request $request, Company $company)
    {
        return $company;
    }

    public function update(Request $request, Company $company)
    {
        if ($request->wantsJson()) {
            $this->validateUpdatedRequest($request->name, $request->value, $company);
            $data = [$request->name => $request->value];
            $company->update($data);
            return $company;
        }

        $this->validate($request, $company->validationRules());
        $company->update($request->all());
        return $company;
    }

    public function destroy(Request $request, Company $company)
    {
        $company->delete();
        return "Company deleted";
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

        Company::whereIn('id', $ids)->delete();
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

        if (!in_array($fieldName, Company::$bulkEditableFields)) {
            abort(403, "Bulk editing the {$fieldName} is not allowed.");
        }

        if (!$items = $request->items) {
            abort(403, "Please select some items.");
        }

        if (!$ids = collect($items)->pluck('id')->all()) {
            abort(403, "No ids provided.");
        }

        $this->validateUpdatedRequest($fieldName, Arr::get($field, 'value'));

        Company::whereIn('id', $ids)->update([$fieldName => Arr::get($field, 'value')]);
        return response("Updated");
    }

    protected function validateUpdatedRequest($field, $value, $company = null)
    {
        if (!$company) $company = new Company();
        $data = [$field => $value];
        $validator = \Validator::make($data, $company->validationRules($field));
        if ($validator->fails()) {
            abort(403, $validator->errors()->first($field));
        }
    }
}
