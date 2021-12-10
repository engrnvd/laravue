<?php
/* @var $company \App\Company */
?>
@extends('emails.common.layout')
@section('content')
    <h3>Hello {{ $company->name }}</h3>

    <p>
        Your data has been registered with us!
    </p>

    @include('emails.common.signature')
@endsection
