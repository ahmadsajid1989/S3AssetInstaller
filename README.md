#Symfony S3 Asset Installer

This bundle adds a console command to facilate the the asset synchronization between your local file system to amazon S3.
The benefit of using this bundle is, it doesnt create ugly keys in s3 bucket. and lets you isolate your production and dev assets.

The installation is very simple. First install the package using composer.

Installation
---------

Require the package in your composer.json:

```
composer require bongo/bongo-s3asset-installer-bundle
```

Then enable it in your Appkernel:

```php
public function registerBundles()
{
    $bundles = [
        ....
        new Bongo\S3AssetInstallerBundle\BongoS3AssetInstallerBundle(),
    ]
}
```

Configuration
---------

You must provided the following parameters:

```yaml

amazon_s3_key: (your key)
amazon_s3_secret: (your secret)
amazon_s3_region:  (your region)
amazon_s3_bucket: (your bucket name without s3://)
```

Then your config.yml add the following configuration:
```yaml
bongo_s3_asset_installer:
    amazon_s3_key: "%amazon_s3.key%"
    amazon_s3_secret: "%amazon_s3.secret%"
    amazon_s3_region: "%amazon_s3.region%"
    amazon_s3_bucket: "%amazon_s3.bucket%"
```

Add the assets_base_url in config_prod.yml
```yaml
framework:    
    assets:
        base_urls:
            - 'http://cdn.production.com/'

```

Add the assets_base_url in your config_dev.yml

```yaml
framework:    
    assets:
        base_urls:
            - 'http://local.localhost.com/'
```

Also, you need change the `write_to` parameter to your s3 bucket:

```yaml
assetic:
    write_to: s3://my_bucket
```


Usage
----

Just use the normal assets commands but using s3 as the target:

First install the assets:
```
php app/console s3:assets:install s3://my-bucket/
``` 

or

```
php bin/console s3:assets:install s3://my-bucket/
```

after asset installation, dump the assetic 

```
php app/console assetic:dump s3://my-bucket
```

or

```
php bin/console assetic:dump s3://my-bucket
```

