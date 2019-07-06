<!DOCTYPE html>
<html>
<head>
    <title>{{ getPageTitle() }} - Home</title>
    <link href="{{ getPublicDirectory('assets/css/styles.css') }}" rel="stylesheet"/>
    <link rel="icon" href="{{ getPublicDirectory('favicon.ico') }}">
</head>
<body>
<div class='center'>
    {# Title #}
    <div class='text-center'>
        <object data="{{ getPublicDirectory('logo.svg') }}" type="image/svg+xml" width="130px"></object>
        <h1 class='title'>{{ $lang['title'] }}</h1>
    </div>

    {# Description #}
    <div class='text-center description'>
        <p>{{ $lang['description'] }}</p>
        <p>{{ $lang['description_2'] }}</p>
    </div>

    {# Options #}
    <div class='text-center options'>
        <a target='_blank' href='https://www.getwolff.com/docs/1.x/home'>{{ upper|$lang['documentation'] }}</a>
        <a target='_blank' href='https://github.com/Usbac/Wolff'>{{ upper|$lang['github'] }}</a>
        <a target='_blank' href='https://github.com/Usbac'>{{ upper|$lang['creators_page'] }}</a>
    </div>

    {# Version #}
    <div class='text-center version'>
        </i>{{ $lang['version'] }}</i>
    </div>
</div>
</body>
</html>
