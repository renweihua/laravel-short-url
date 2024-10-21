@extends('shorturl::layouts.app',  ['title' => trans('url.forbidden.short')])
@section('content')
    <div class="header bg-gradient-primary mb-3 pt-6 d-none d-lg-block d-md-block pt-md-7"></div>
    <div class="container-fluid col-lg-10 col-sm-12 m-auto">
        <div class="header-body">
            <div class="row">
                <div class="container-fluid">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header d-flex justify-content-between">
                                <h1>{{ __('url.forbidden.short') }}
                                    <a href="/{{$data['url']->short_url}}">{{$data['url']->short_url}}</a>
                                </h1>
                                <form action="/url/{{$data['url']->short_url}}" method="POST" id="deleteUrl">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="submit" class="btn btn-danger" value="{{ __('url.delete.delete') }}"
                                           onclick="confirmDelete()">
                                </form>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())

                                    <div class="alert alert-danger">
                                        <p>{{ __('urlhum.error') }}:</p>
                                        <ul>
                                            @foreach ($errors->all() as $error)

                                                <li>{{ $error }}</li>
                                            @endforeach

                                        </ul>
                                    </div>
                                @endif

                                @if(Session::has('success'))

                                    <div class="alert alert-success">
                                        <p>{{ Session::get('success') }}</p>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between">
                                    <p class="pull-left">
											<span class="badge badge-info">{{ __('url.created', ['date' => formatting_timestamp($data['url']->created_time)]) }}</span>
                                    </p>
                                    <p class="pull-right">
											<span class="badge badge-light">{{ __('url.updated', ['date' => formatting_timestamp($data['url']->updated_time)]) }}</span>
                                    </p>
                                </div>
                                @if(!empty($data['url']->website_name))
                                    <p>
                                        <span class="badge badge-default">{{ __('url.website_name') }}</span> {{$data['url']->website_name}}
                                    </p>
                                @endif
                                <p>
                                    <span class="badge badge-default">{{ __('url.destination') }}</span> &nbsp;{{$data['url']->long_url}}
                                </p>
                                <p>
                                    <span class="badge badge-danger">{{ __('url.by') }}</span> &nbsp;
                                    @if($data['url']->user->email != "Anonymous")
                                        <a href="/user/{{$data['url']->user->id}}/edit">{{$data['url']->user->name}}</a> -
                                    @endif
                                    {{$data['url']->user->email}}
                                </p>
                                <a href="/{{$data['url']->short_url}}+" class="btn btn-success">
                                    <i class="fa fa-chart-bar"></i> {{ __('url.stats') }}
                                </a>

                                <button type="button" class="btn btn-info" id="qrModalButton" data-toggle="modal" data-target="#QRCodeModal">
                                    <i class="fa fa-qrcode"></i> {{ __('url.qrcode') }}
                                </button>

                                <hr>

                                <div>
                                    <form method="POST" action="{{ route('url.forbidden', $data['url']->short_url) }}">
                                        @csrf

                                        <input type="hidden" name="_method" value="PUT">
                                        <label class="text-left" for="is_forbidden" style="float:left;">{{ __('url.options.forbidden') }}</label>
                                        <div class="form-group text-right" id="privateUrlcontainer">
                                            <label class="custom-toggle">
                                                <input type="hidden" name="is_forbidden" value="0">
                                                <input type="checkbox" name="is_forbidden" value="1" @if ($data['url']->is_forbidden == 1) checked @endif >
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                        <label class="text-left" for="admin_remarks" style="float:left;">{{ __('url.remarks') }}</label>
                                        <div class="form-group">
                                            <textarea class="form-control" name="admin_remarks" rows="3" onmousedown="setCursorToStart(event)">
                                                {{$data['url']->admin_remarks}}
                                            </textarea>
                                        </div>
                                        <button type="submit" class="btn btn-secondary">{{ __('urlhum.save') }}</button>
                                    </form>
                                </div>
                            </div>

                            @include('shorturl::url.partials.qrcodemodal', ['url' => $data['url']->short_url])

                        </div>
                    </div>
                </div>
            </div>
            @include('shorturl::layouts.footers.auth')
        </div>
    </div>


@endsection
@push('js')

    <script src="/js/app.js"></script>
    <script>
        $("#deleteUrl").submit(function (e) {
            if (confirm("{{ __('url.delete.confirm') }}")) {
                $("#deleteUrl")[0].submit();
            }
            e.preventDefault();
        });

        // 点击光标设置到初始位置（没效果……下机！）
        function setCursorToStart(event) {
            var textarea = event.target;
            textarea.focus();

            if (textarea.setSelectionRange) {
                textarea.setSelectionRange(0, 0);

                // 设置光标位置到初始位置（这里的0表示初始位置）
                textarea.selectionStart = 0;
                textarea.selectionEnd = 0;
            } else if (textarea.createTextRange) {
                var range = textarea.createTextRange();
                range.collapse(true);
                range.select();
            }
        }
    </script>

@endpush
