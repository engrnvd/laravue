<?php
/* @var $user \App\User */
/* @var $url string */
/* @var $verificationUrl string */
?>
@extends('emails.common.layout')
@section('content')
    @include('emails.common.salutation',['first_name'=>$user->first_name])

    <p>
        You are receiving this email because we received a password reset request for your {{ config('app.name') }}
        account.
    </p>
    @include('emails.common.action_btn',['url'=>$resetUrl,'text'=>'Reset Password'])
    <p>
        This password reset link will expire in {{config('auth.passwords.users.expire')}} minutes.
    </p>
    <p>
        If you did not request a password reset, no further action is required.
    </p>
    @include('emails.common.signature')
@endsection