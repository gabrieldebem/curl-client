<?php
namespace Gabrieldebem\Curl;

use CurlHandle;

abstract class CurlClient
{
    public CurlHandle $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    private function defineDefaultOptions(string $url): self
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        return $this;
    }

    public function headers(array $headers): self
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    public function get(string $url, ?array $filters = null): string|bool
    {
        if ($filters) {
            $url = $url . http_build_query($filters);
        }

        return $this->defineDefaultOptions(
            url: $url
        )->execute();
    }

    public function post(string $url, ?array $data = null)
    {
        curl_setopt($this->curl, CURLOPT_POST, 1);
        
        if ($data) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        return $this->defineDefaultOptions(
            url: $url
        )->execute();
    }

    public function put(string $url, ?array $data = null)
    {
        curl_setopt($this->curl, CURLOPT_PUT, 'PUT');
        
        if ($data) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        return $this->defineDefaultOptions(
            url: $url
        )->execute();
    }

    public function patch(string $url, ?array $data = null)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        
        if ($data) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        return $this->defineDefaultOptions(
            url: $url
        )->execute();
    }

    public function delete(string $url)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');

        return $this->defineDefaultOptions(
            url: $url
        )->execute();
    }

    private function execute(): string|bool
    {
        $response = curl_exec($this->curl);
        curl_close($this->curl);

        return $response;
    }
}