<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Models\TinyUrl;

class RedirectTinyUrlRequest extends BaseRequest
{
    /**
     * @var string
     */
    protected $url;

    /**
     * Constructs the class
     */
    public function __construct()
    {
    }

    /**
     * Processes the request and return the response
     */
    public function process(): string
    {
        $tinyUrl = new TinyUrl();
        $longUrl = $tinyUrl->getLongUrlFromDb($this->url);
        return $longUrl;
    }


    /**
     * Populates the property values based on requestData
     */
    public function populate(array $requestData): void
    {
        $this->url = $requestData['url'];
    }

    /**
     * Get the validation data
     */
    public function getValidationData(): array
    {
        return [
            'url' => $this->url,
        ];
    }

    /**
     * Get the validation rules
     */
    public function getValidationRules(): array
    {
        return [
            'url' => ['required', 'string'],
        ];
    }
}