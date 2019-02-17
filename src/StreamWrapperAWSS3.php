<?php
/**
 * Created by PhpStorm.
 * User: Ahmad Sajid
 * Date: 2/7/2019
 * Time: 1:00 PM
 */

namespace Bongo\S3AssetInstallerBundle;


use Aws\S3\S3Client;


/**
 * Class StreamWrapperAWSS3
 * @package Bongo\S3AssetInstallerBundle
 */
class StreamWrapperAWSS3
{
    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * StreamWrapperAWSS3 constructor.
     *
     * @param $key
     * @param $secret
     * @param $region
     */
    public function __construct($key, $secret, $region) {

        $aws = array(
            'key'    => $key,
            'secret' => $secret,
            'region' => $region,
            'version' => 'latest'

        );

        $this->s3Client = new S3Client($aws);
    }

    /**
     * Registering the Stream Wrapper class
     */
    public function registerStreamWrapper() {
        $this->s3Client->registerStreamWrapper();
    }

}