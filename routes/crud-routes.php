<?php
Route::resource('users', 'UserController');

Route::resource('companies', 'CompanyController');
Route::post('/companies/change-pic/{company}', 'CompanyController@changePic');

Route::resource('employees', 'EmployeeController');

