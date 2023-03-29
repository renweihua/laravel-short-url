<?php

namespace App\Modules\ShortUrl\Http\Controllers\Admin;

use App\Models\ShortUrl;
use App\Models\ShortUrlClick;
use App\Modules\ShortUrl\Http\Controllers\ShortUrlController;
use App\Modules\ShortUrl\Http\Requests\ShortUrlRequest;
use App\Modules\ShortUrl\Services\UrlService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class UrlController extends ShortUrlController
{
    /**
     * @var UrlService
     */
    protected $url;
    protected $deviceDetection;

    /**
     * UrlController constructor.
     * @param UrlService $urlService
     */
    public function __construct(UrlService $urlService)
    {
        parent::__construct();
        $this->url = $urlService;
    }

    /**
     * Show the admin all the Short URLs.
     *
     * @return Factory|View
     */
    public function showUrlsList(Request $request)
    {
        return view('shorturl::url.list');
    }

    /**
     * AJAX load of all the Short URLs to show in the admin URLs list.
     *
     * @return mixed
     * @throws Exception
     */
    public function loadUrlsList()
    {
        // Here we add a column with the buttons to show analytics and edit short URLs.
        // There could be a better way to do this.
        // TODO: Really NEED to find a better way to handle this. It's horrible.
        $dataTable = DataTables::of(ShortUrl::with('user:user_id')->orderByDesc('id')->get())
            ->addColumn('action', function ($row) {
                return '<a href="/'.$row->short_url.'+"><button type="button" class="btn btn-secondary btn-sm btn-url-analytics"><i class="fa fa-chart-bar" alt="Analytics"> </i> '.trans('analytics.analytics').'</button></a> &nbsp;
                       <a href="/url/'.$row->short_url.'"><button type="button" class="btn btn-success btn-sm btn-url-edit"><i class="fa fa-pencil-alt" alt="Edit"> </i>'.trans('urlhum.edit').'</button></a>';
            })
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }
}
