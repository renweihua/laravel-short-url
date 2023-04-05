<?php

namespace App\Modules\ShortUrl\Http\Controllers\Admin;

use App\Models\Url;
use App\Models\UrlClick;
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
        $lists = Url::with('user.userInfo')->orderByDesc('id')->get();

        foreach ($lists as $item){
            $item->show_created_time = formatting_timestamp($item->created_time);
        }
        if ($lists){
            $lists = $lists->toArray();
            foreach ($lists as &$item){
                if (!$item['user']){
                    $item['user'] = [
                        'user_info' => [
                            'nick_name' => '游客'
                        ]
                    ];
                }
            }
        }

        $dataTable = DataTables::of($lists)
            ->addColumn('action', function ($row) {
                return '<a href="/'.$row['short_url'].'+"><button type="button" class="btn btn-secondary btn-sm btn-url-analytics"><i class="fa fa-chart-bar" alt="Analytics"> </i> '.trans('analytics.analytics').'</button></a> &nbsp;
                       <a href="/url/'.$row['short_url'].'"><button type="button" class="btn btn-success btn-sm btn-url-edit"><i class="fa fa-pencil-alt" alt="Edit"> </i>'.trans('urlhum.edit').'</button></a>';
            })
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }
}
