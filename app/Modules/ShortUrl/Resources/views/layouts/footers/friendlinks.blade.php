@if($friendlinks)
    <div class="m-auto">
        <footer class="footer" style="margin-top: -2.5rem;">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-12">
                    <ul class="nav nav-footer">
                        <li class="nav-item">
                            友情链接：
                        </li>
                        @foreach($friendlinks as $friendlink)
                            <li class="nav-item">
                                <a href="{{ $friendlink->link_url }}" class="nav-link" target="_blank">{{ $friendlink->link_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </footer>
    </div>
@endif
