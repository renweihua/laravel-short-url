@extends('shorturl::layouts.app', ['title' => __('urlhum.dashboard')])

@section('content')
    @include('shorturl::layouts.headers.cards')
    <div class="container-fluid col-lg-10  col-md-10 col-sm-12 mt--7">
        @if (Auth::check() || setting('anonymous_urls'))
            @include('shorturl::widgets/create-url')
        @endif
        <div class="row mt-5">
            @isset($publicUrls)
                @include('shorturl::widgets/latests-urls')
            @endisset

            @isset($referers)
                @include('shorturl::widgets/referrers')
            @endisset
        </div>
        @include('shorturl::layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="/js/app.js"></script>
@endpush
