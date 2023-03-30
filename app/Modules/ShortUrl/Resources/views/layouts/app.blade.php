<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $title ?? ''}} - {{ setting('website_name') }}</title>
    <!-- Favicon -->
    <link href="{{ setting('website_favicon') }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="/css/app.css" rel="stylesheet" />
    <!-- 百度统计 -->
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?6bbb0471b1634891db6ff73f60fce088";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body class="{{ $class ?? '' }}">
@auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('shorturl::layouts.navbars.sidebar')
@endauth

<div class="main-content">
    @include('shorturl::layouts.navbars.navbar')
    @yield('content')
</div>





@stack('js')
{!! setting('custom_html') !!}


</body>
</html>
