<?php

namespace SteffjeNL\LaravelAzureBlobStorage;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use function sprintf;

/**
 * Service provider for Azure Blob Storage
 */
class AzureBlobStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('azure', function ($app, $config) {
            $endpoint = sprintf(
                'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
                $config['name'],
                $config['key']
            );

            $client = BlobRestProxy::createBlobService($endpoint);
            return new Filesystem(new AzureBlobStorageExtendedAdapter($client, $config['container'], $config['prefix'], $config['key'], $config['url']));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
