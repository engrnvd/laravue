<?php
/* @var $url string */
$logo = config('app.api_url')."/images/logo.png"
?>
<div style="text-align: center; padding: 18px;
    border-radius: 3px;">
    <img style="width: 250px"
         src="{{isset($message) ? $message->embed($logo) : $logo}}"
         alt="{{ config('app.name') }} Logo">
</div>
