<?php

namespace SteffjeNL\LaravelAzureBlobStorage;

use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use function sprintf;

class AzureBlobStorageExtendedAdapter extends AzureBlobStorageAdapter
{
    /**
     * @var BlobRestProxy
     */
    private $client;

    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $accountKey;

    public function __construct(BlobRestProxy $client, $container, $prefix = null, $accountKey = null, $baseUrl = null)
    {
        $this->client = $client;
        $this->container = $container;
        $this->baseUrl = $baseUrl;
        $this->accountKey = $accountKey;
        $this->setPathPrefix($prefix);
        parent::__construct($client, $container, $prefix);
    }

    public function getTemporaryUrl($path, $ttl, $options)
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

    public function getUrl($path, $sasKey = '')
    {
        if ( ! empty($this->baseUrl)) {
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

}