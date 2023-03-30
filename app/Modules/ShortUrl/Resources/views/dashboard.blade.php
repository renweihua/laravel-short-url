@extends('shorturl::layouts.app', ['title' => __('urlhum.dashboard')])

@section('content')
    @include('shorturl::layouts.headers.cards')
    <div class="container-fluid col-lg-10 col-md-10 col-sm-12 mt--7">

        <!-- 切换语言成功提示 -->
        @if(Session::has('change-language-success'))
            <div class="alert alert-success">
                {{ Session::get('change-language-success') }}
            </div>
        @endif

        <div class="alert alert-secondary">{{ __('urlhum.home_propose') }}</div>

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

        @include('shorturl::layouts.footers.friendlinks')
    </div>
@endsection

@push('js')
    <script src="/js/app.js"></script>
@endpush
