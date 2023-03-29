<?php

namespace App\Modules\ShortUrl\Http\Controllers;

use App\Exceptions\Exception;
use App\Models\Url;
use App\Models\UrlClick;
use App\Modules\ShortUrl\Http\Requests\MultipleUrlsRequest;
use App\Modules\ShortUrl\Http\Requests\ShortUrlRequest;
use App\Modules\ShortUrl\Services\UrlService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UrlMultipleController extends ShortUrlController
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

    public function createMultiple()
    {
        return view ('shorturl::url.multiple');
    }

    public function storeMultiple(MultipleUrlsRequest $multipleUrls): RedirectResponse
    {
        $data = $multipleUrls->validated();
        $siteUrl = request()->getHttpHost();

        // Split every URL by \n\r
        $urls = preg_split('/$\R?^/m', $data['urls']);

        foreach ($urls as $key => $url) {
            $urls[$key] = trim($url);
        }

        $errors = [];
        $existing = 0;
        $shortened = [];

        foreach ($urls as $key => $url) {
            $validator = Validator::make([$url], [$key => 'url']);
            if ($validator->fails()) {
                $errors[] = 'The URL ' . $url . ' is not valid.';
            }
        }

        if (count($errors) > 0) {
            $multipleUrls->flash();
            return Redirect::route('multiple')
                ->with('errors', $errors);
        }

        foreach ($urls as $key => $url) {
            if ($shortUrl = $this->url->checkExistingLongUrl($url)) {
                $shortened[] = $shortUrl;
                $existing++;

            } else {
                try {
                    $url = $this->url->shortenUrl($url, null, $data['privateUrl'], $data['hideUrlStats']);
                } catch (Exception $ex) {
                    return Redirect::route('multiple')
                        ->with('errors', 'Error. Please try again.');
                }

                $shortened[] = $url->short_url;
            }
        }

        return Redirect::route('multiple')
            ->with('shortened', $shortened)
            ->with('existing', $existing)
            ->with('siteUrl', $siteUrl)
            ;
    }
}
