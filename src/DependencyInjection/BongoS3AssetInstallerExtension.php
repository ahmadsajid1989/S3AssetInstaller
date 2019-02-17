<?php
/**
 * Created by PhpStorm.
 * User: Ahmad Sajid
 * Date: 2/17/2019
 * Time: 11:38 AM
 */

namespace Bongo\S3AssetInstallerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class BongoS3AssetInstallerExtension
 * @package Bongo\S3AssetInstallerBundle\DependencyInjection
 */
class BongoS3AssetInstallerExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container,new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bongo_s3_asset_installer.aws_s3_key', $config['aws_s3_key']);
        $container->setParameter('bongo_s3_asset_installer.aws_s3_secret', $config['aws_s3_secret']);
        $container->setParameter('bongo_s3_asset_installer.aws_s3_region', $config['aws_s3_region']);
        $container->setParameter('bongo_s3_asset_installer.aws_s3_bucket',$config['aws_s3_bucket']);

    }
}