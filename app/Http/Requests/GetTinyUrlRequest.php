<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Models\TinyUrl;

class GetTinyUrlRequest extends BaseRequest
{
    
    protected $url;

    protected $expiryDate;

    /**
     * Constructs the class
     */
    public function __construct()
    {
    }

    /**
     * Processes the request and returns the response
     */
    public function process(): string
    {
        $shortUrl = new TinyUrl();
        $tinyUrl = $shortUrl->getTinyUrlFromLongUrl($this->url, $this->expiryDate);
        return $tinyUrl;
    }

    /**
     * Populates the property values based on requestData
     */
    public function populate(array $requestData): void
    {
        $this->url = $requestData['url'] ?? '';
        $this->expiryDate = $requestData['expiryDate'] ?? null;
    }

    /**
     * Get the validation data
     */
    public function getValidationData(): array
    {
        return [
            'url' => $this->url,
            'expiryDate' => $this->expiryDate
        ];
    }

    /**
     * Get the validation rules
     */
    public function getValidationRules(): array
    {
        return [
            'url' => ['required', 'string'],
            'expiryDate' => ['nullable', 'date_format:Y-m-d', 'after:today']
        ];
    }
}