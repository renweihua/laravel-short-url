@auth()
    @include('shorturl::layouts.navbars.navs.auth')
@endauth

@guest()
    @include('shorturl::layouts.navbars.navs.guest')
@endguest
