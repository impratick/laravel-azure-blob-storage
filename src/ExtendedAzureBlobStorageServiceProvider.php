<?php

namespace Impratick\ExtendedAzureBlobStorage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

class ExtendedAzureBlobStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Storage::extend('azure', function ($app, $config) {
            $connectionString = sprintf(
                'DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;EndpointSuffix=%s',
                isset($config['protocol']) ? $config['protocol'] : 'https',
                $config['account']['name'],
                $config['account']['key'],
                $config['endpoint-suffix']
            );

            $client = BlobRestProxy::createBlobService($connectionString);

            return new Filesystem(new AzureBlobStorageAdapter($client, $config['container'], ($config['prefix'] ?? null)));
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filesystems.php', 'filesystems');
    }
}
