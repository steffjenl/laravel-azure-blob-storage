<?php

namespace SteffjeNL\LaravelAzureBlobStorage;

use DateTime;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use function sprintf;

/**
 * Class AzureBlobStorageExtendedAdapter
 *
 * @package   laravel-azure-blob-storage
 * @author    Stephan Eizinga <stephan@monkeysoft.nl>
 * @copyright 2018 Stephan Eizinga
 * @link      https://github.com/steffjenl/laravel-azure-blob-storage
 */
class AzureBlobStorageExtendedAdapter extends AzureBlobStorageAdapter
{
    private BlobRestProxy $client;
    private string $container;
    private ?string $baseUrl;
    private ?string $accountKey;

    /**
     * AzureBlobStorageExtendedAdapter constructor.
     *
     * @param BlobRestProxy $client
     * @param string $container
     * @param string $prefix
     * @param string|null $accountKey
     * @param string|null $baseUrl
     */
    public function __construct(BlobRestProxy $client, string $container, string $prefix = '', ?string $accountKey = null, ?string $baseUrl = null)
    {
        $this->client = $client;
        $this->container = $container;
        $this->baseUrl = $baseUrl;
        $this->accountKey = $accountKey;
        parent::__construct($client, $container, $prefix);
    }

    /**
     * Generate Temporary Url with SAS query
     *
     * @param string $path
     * @param DateTime|string $ttl
     * @param $options
     * @return string
     */
    public function getTemporaryUrl(string $path, DateTime|string $ttl, $options): string
    {
        $sas = new BlobSharedAccessSignatureHelper($this->client->getAccountName(), $this->accountKey);
        $sasString = $sas->generateBlobServiceSharedAccessSignatureToken(
            Resources::RESOURCE_TYPE_BLOB
            , $this->container . '/' . $path
            , 'r'
            , $ttl
            , ''
            , ''
            , 'https');

        return $this->getUrl($path, sprintf('?%s', $sasString));
    }

    /**
     * Generate public url
     *
     * @param string $path
     * @param string $sasKey
     * @return string
     */
    public function getUrl(string $path, string $sasKey = ''): string
    {
        if (!empty($this->baseUrl)) {
            return sprintf('%s/%s/%s%s'
                , $this->baseUrl
                , $this->container
                , $path
                , $sasKey);
        }

        return sprintf('https://%s.blob.core.windows.net/%s/%s%s'
            , $this->client->getAccountName()
            , $this->container
            , $path
            , $sasKey);
    }

    public function getClient(): BlobRestProxy
    {
        return $this->client;
    }
}