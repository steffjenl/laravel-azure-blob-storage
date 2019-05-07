<?php

namespace SteffjeNL\LaravelAzureBlobStorage;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use function sprintf;
use function strpos;

/**
 * Class AzureBlobStorageServiceProvider
 *
 * Service provider for Azure Blob Storage
 *
 * @package   laravel-azure-blob-storage
 * @author    Stephan Eizinga <stephan@monkeysoft.nl>
 * @copyright 2018 Stephan Eizinga
 * @link      https://github.com/steffjenl/laravel-azure-blob-storage
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
            if (!empty($config['local_address']) && $config['local_address'] == 'local')
            {
                $endpoint = 'UseDevelopmentStorage=true';
            }
            elseif (!empty($config['local_address']) && $config['local_address'] != 'local')
            {
                $endpoint = sprintf(
                    'DefaultEndpointsProtocol=http;AccountName=%s;AccountKey=%s;BlobEndpoint=%s;',
                    $config['name'],
                    $config['key'],
                    $config['local_address']
                );
            }
            else
            {
                $endpoint = sprintf(
                    'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s;',
                    $config['name'],
                    $config['key']
                );

                /*
                 * When sig= is found in the key, we assume that we must build a ConnectionString from the SAS principle.
                 */
                if (strpos($config['key'],'sig=') !== false)
                {
                    $endpoint = sprintf(
                        'BlobEndpoint=https://%s.blob.core.windows.net;SharedAccessSignature=%s',
                        $config['name'],
                        $config['key']
                    );
                }
            }

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
