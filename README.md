# Laravel Azure Blob Storage

Laravel Azure Blob Storage wrapper for [Flysystem Azure Blob Storage](https://flysystem.thephpleague.com/docs/adapter/azure/) with prefix support and easy integration with [Spatie's Media Library](https://docs.spatie.be/laravel-medialibrary).

Package includes:
* A Service Provider for Laravel
    * Adding an `azure` disk for Laravel's File Storage abstraction of [Flysystem](https://github.com/thephpleague/flysystem)
* Integration with [Spatie's Media Library](https://docs.spatie.be/laravel-medialibrary) providing
    * A `AzureBlobUrlGenerator` (https://docs.spatie.be/laravel-medialibrary/v7/advanced-usage/generating-custom-urls)

## Installation

You can install the package via composer:

```bash
composer require impratick/laravel-azure-blob-storage
```

## Usage
The Service Provider is automatically registered on **Laravel >= 5.5**.

Configure your disk in `config/filesystem.php`

``` php
    'disks' => [
        'azure' => [
            'driver'          => 'azure',
            'account'         => [
                'name' => env('AZURE_ACCOUNT_NAME'),
                'key'  => env('AZURE_ACCOUNT_KEY'),
            ],
            'endpoint-suffix' => env('AZURE_ENDPOINT_SUFFIX', 'core.windows.net'),
            'container'       => env('AZURE_CONTAINER', 'public'),
            'prefix'          => env('AZURE_BLOB_FOLDER_NAME', null),
        ]
    ]
```

Here **prefix** is needed if you have custom folder structure i.e.
```
• project-storage
    • local_media / media folders
	• staging_media / media folders
	• production_media / media folders
```
So as per above scenario value of **prefix** would be as per environment **(local_media/staging_media/production_media)**

### For integration with Media Library

Install and configure [Media Library](https://docs.spatie.be/laravel-medialibrary/v7/installation-setup/).

Add the following to `config/medialibrary.php`

```php
    'azure' => [
        'domain'    => 'https://' . env('AZURE_ACCOUNT_NAME') . '.blob.' . env('AZURE_ENDPOINT_SUFFIX') . '/' . env('AZURE_CONTAINER') . (env('AZURE_BLOB_FOLDER_NAME') ? '/' . env('AZURE_BLOB_FOLDER_NAME') : ''),
    ],

     /*
     * When urls to files get generated, this class will be called. Leave empty
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => (env('MEDIA_DISK', 'public') == 'azure' ? Impratick\ExtendedAzureBlobStorage\MediaLibrary\UrlGenerator\AzureBlobUrlGenerator::class : null),
```

### Changelog

Please review [CHANGELOG](CHANGELOG.md) for more information.

### Security

If you discover any security related issues, please fill free to report at padia.pratik94@gmail.com.

## Credits

* [Flysystem](https://github.com/thephpleague/flysystem)
* [All Contributors](../../contributors)

This package was made based on [A skeleton repository for Spatie's PHP Packages](https://github.com/spatie/skeleton-php).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.