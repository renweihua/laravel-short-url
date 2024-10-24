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
        $lists = Url::with('user:id,email')->orderByDesc('id')->get();

        foreach ($lists as $item){
            $item->show_created_time = formatting_timestamp($item->created_time);
        }

        $dataTable = DataTables::of($lists)
            ->addColumn('action', function ($row) {
                $html = '<a href="/'.$row->short_url.'+"><button type="button" class="btn btn-secondary btn-sm btn-url-analytics"><i class="fa fa-chart-bar" alt="Analytics"> </i> '.trans('analytics.analytics').'</button></a> &nbsp;
                       <a href="/url/'.$row->short_url.'"><button type="button" class="btn btn-success btn-sm btn-url-edit"><i class="fa fa-pencil-alt" alt="Edit"> </i>'.trans('urlhum.edit').'</button></a> &nbsp;';
                if ($row->is_forbidden == 1){
                    $html .= '<a href="'. route('url.forbidden', $row->short_url) .'"><button type="button" class="btn btn-success btn-sm btn-url-edit"><i class="fa fa-unlock" alt="Unblock"> </i>'.trans('urlhum.unblock').'</button></a>';
                }else{
                    $html .= '<a href="'. route('url.forbidden', $row->short_url) .'"><button type="button" class="btn btn-danger btn-sm btn-url-edit"><i class="fa fa-lock" alt="Forbidden"> </i>'.trans('urlhum.forbidden').'</button></a>';
                }
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);

        return $dataTable;
    }

    /**
     * 禁用
     *
     * @return Factory|View
     */
    public function forbidden($url, Request $request)
    {
        $url = Url::with('user:id,name,email')->whereRaw('BINARY `short_url` = ?', [$url])->firstOrFail();

        if (! $this->url->OwnerOrAdmin($url) ) {
            abort(403);
        }

        if ($request->isMethod('PUT')){
            $data = $request->all();

            $url->is_forbidden = (int)$data['is_forbidden'];
            if ($url->is_forbidden == 1){
                $url->forbidden_time = time();
            }
            if (empty($data['admin_remarks'])) $data['admin_remarks'] = '';
            $url->admin_remarks = trim($data['admin_remarks']);
            $url->update();

            return Redirect::route('url.list')
                ->with('success',  $url->is_forbidden == 1 ? 'Short URL forbidden successfully.' : 'Short URL unblocked successfully.');
        }

        $data['url'] = $url;

        return view('shorturl::url.forbidden')->with('data', $data);
    }
}
