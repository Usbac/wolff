<!DOCTYPE html>
<html>
<head>
    <title>{{PAGE_TITLE}} - Home</title>
    <link href="{{APP}}assets/css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class='center'>
        <div class='text-center'>
            <img class='main-img' src='{{PUBLIC_DIR}}logo.png' width='130px'>
            <h1 class='title'>{{ $lang['title'] }}</h1>
        </div>
        <p class='description text-center'>{{ $lang['description'] }}</p>
        <div class='text-center options'>
            <a target='_blank' href='https://github.com/Usbac/Wolff/wiki'>{{ upper|$lang['documentation'] }}</a>
            <a target='_blank' href='https://github.com/Usbac/Wolff'>{{ upper|$lang['github'] }}</a>
            <a target='_blank' href='https://github.com/Usbac'>{{ upper|$lang['creators_page'] }}</a>
        </div>
        <div class='text-center version'>
            </i>{{ $lang['version'] }}</i>
        </div>
    </div>
</body>
</html>