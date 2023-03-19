<?php

namespace App\Modules\ShortUrl\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShortUrlRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url' => 'required|max:500|url',
            'customUrl' => 'nullable|min:4|max:15|regex:/^[-a-zA-Z0-9_]+$/',
            'privateUrl' => 'boolean',
            'hideUrlStats' => 'boolean',
            'windows' => 'nullable|string|max:500|url',
            'macos' => 'nullable|string|max:500|url',
            'ios' => 'nullable|string|max:500|url',
            'android' => 'nullable|string|max:500|url',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! Auth::check() && ! setting('anonymous_urls')) {
            return false;
        }

        return true;
    }
}
