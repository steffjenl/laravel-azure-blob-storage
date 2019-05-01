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
            'driver'            => 'azure',
            'local_address'     => env('AZURE_STORAGE_LOCAL_ADDRESS', null),
            'name'              => env('AZURE_STORAGE_NAME'),
            'key'               => env('AZURE_STORAGE_KEY'),
            'container'         => env('AZURE_STORAGE_CONTAINER'),
            'prefix'            => env('AZURE_STORAGE_PREFIX', null),
            'url'               => env('AZURE_STORAGE_URL', null),
        ],
```

Finally, add the fields `AZURE_STORAGE_NAME`, `AZURE_STORAGE_KEY` and `AZURE_STORAGE_CONTAINER` to your `.env` file with the appropriate credentials. Then you can set the `azure` driver as either your default or cloud driver and use it to fetch and retrieve files as usual.

# Configuration

If you use a DSN you can enter your DNS address in the `AZURE_STORAGE_URL` field

If you want to use a prefix so that you always work in a specific folder, you can use the `AZURE_STORAGE_PREFIX` field

If you want to use Storage Emulator you can set `AZURE_STORAGE_LOCAL_ADDRESS` to local. If your storage emulator is on an external machine you can change the `AZURE_STORAGE_LOCAL_ADDRESS` other than local.
When using `local` the connection string will be `UseDevelopmentStorage=true`.

## Example config for local emulator
```php
AZURE_STORAGE_LOCAL_ADDRESS=local
AZURE_STORAGE_NAME=devstoreaccount1
AZURE_STORAGE_KEY=Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==
AZURE_STORAGE_CONTAINER=CONTAINER_NAME_HERE
```

## Example config for remote emulator
```php
AZURE_STORAGE_LOCAL_ADDRESS=http://192.168.0.2:10000/devstoreaccount1
AZURE_STORAGE_NAME=devstoreaccount1
AZURE_STORAGE_KEY=Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==
AZURE_STORAGE_CONTAINER=CONTAINER_NAME_HERE
```

