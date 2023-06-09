@extends('shorturl::layouts.app', ['title' => trans('urlhum.termsofuse')])
@section('content')
    <div class="header bg-gradient-primary mb-3 pt-6 	d-none d-lg-block d-md-block pt-md-7">
    </div>
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">
                <div class="container-fluid">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h1 class="text-center">{{ __('urlhum.termsofuse') }}</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        {!! nl2br(setting('terms_of_use')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('shorturl::layouts.footers.auth')
    </div>


@endsection
@push('js')
    <script src="/js/app.js"></script>
@endpush
