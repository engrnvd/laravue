<?php
/* @var $user \App\User */
/* @var $url string */
/* @var $verificationUrl string */
?>
@extends('emails.common.layout')
@section('content')
    @include('emails.common.salutation',['first_name'=>$user->first_name])

    <p>
        We are glad to have you on {{ config('app.name') }}.
        To get started, please click the button below to verify your account.
    </p>

    @include('emails.common.action_btn',['url'=>$verificationUrl,'text'=>'Verify Email'])
    @include('emails.common.signature')
@endsection

