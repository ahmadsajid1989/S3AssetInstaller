<?php
/**
 * Created by PhpStorm.
 * User: Ahmad Sajid
 * Date: 2/17/2019
 * Time: 11:38 AM
 */

namespace Bongo\S3AssetInstallerBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class BongoS3AssetInstallerBundle extends Bundle
{
    public function boot()
    {
        parent::boot();
        $key = $this->container->getParameter('bongo_s3_asset_installer.aws_s3_key');
        $secret = $this->container->getParameter('bongo_s3_asset_installer.aws_s3_secret');
        $region = $this->container->getParameter('bongo_s3_asset_installer.aws_s3_region');

        $s3Client = new StreamWrapperAWSS3($key, $secret, $region);
        $s3Client->registerStreamWrapper();
    }

}