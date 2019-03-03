<!DOCTYPE html>
<html>
<head>
    <title>{{PAGE_TITLE}} - Home</title>

    <link href="{{APP}}view/css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class='center'>
        <div class='text-center'>
            <img class='main-img' src='{{APP}}view/logo.png' width='100px'>
            <h1 class='title'>{{ $lang['title'] }}</h1>
        </div>
        <p class='description text-center'>{{ $lang['description'] }}</p>
        <div class='text-center options'>
            <a target='_blank' href='https://github.com/Usbac/Wolff/wiki'>{{ $lang['documentation'] }}</a>
            <a target='_blank' href='https://github.com/Usbac/Wolff'>GITHUB</a>
            <a target='_blank' href='https://github.com/Usbac'>{{ $lang['creators_page'] }}</a>
        </div>
        <p class='text-center opacity-5'>{{$lang['remember']}}</p>
    </div>
</body>
</html>
