# laravel-azure-blob-storage
[![Build Status](https://travis-ci.org/steffjenl/laravel-azure-blob-storage.svg?branch=master)](https://travis-ci.org/steffjenl/laravel-azure-blob-storage)

Microsoft Azure Blob Storage integration for Laravel's Storage API

This package uses the new azure storage blob package and extends the AzureBlobStorageAdapter package for specific Laravel functions. You can also use this on frameworks with Flysystem Filesystem support.

# Installation

Install the package using composer:

```bash
composer require steffjenl/laravel-azure-blob-storage
```

On Laravel versions before 5.5 you also need to add the service provider to `config/app.php` manually:

```php
    SteffjeNL\LaravelAzureBlobStorage\AzureBlobStorageServiceProvider::class,
```

Then add this to the `disks` section of `config/filesystems.php`:

```php
        'azure' => [
            'driver'    => 'azure',
            'name'      => env('AZURE_STORAGE_NAME'),
            'key'       => env('AZURE_STORAGE_KEY'),
            'container' => env('AZURE_STORAGE_CONTAINER'),
            'prefix'    => env('AZURE_STORAGE_PREFIX', null),
            'url'       => env('AZURE_STORAGE_URL', null),
        ],
```

Finally, add the fields `AZURE_STORAGE_NAME`, `AZURE_STORAGE_KEY` and `AZURE_STORAGE_CONTAINER` to your `.env` file with the appropriate credentials. Then you can set the `azure` driver as either your default or cloud driver and use it to fetch and retrieve files as usual.

# Configuration

If you use a DSN you can enter your DNS address in the `AZURE_STORAGE_URL` field

If you want to use a prefix so that you always work in a specific folder, you can use the `AZURE_STORAGE_PREFIX` field