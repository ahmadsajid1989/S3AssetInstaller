<?php
/**
 * Created by PhpStorm.
 * User: Ahmad Sajid
 * Date: 2/17/2019
 * Time: 12:51 PM
 */

namespace Bongo\S3AssetInstallerBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bongo_s3_asset_installer');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('bongo_s3_asset_installer');
        }

        $rootNode
            ->children()
            ->scalarNode('aws_s3_key')->info("Your AWS Access id/key")
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('aws_s3_secret')->info("Your AWS Secret Key")
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('aws_s3_region')->info('Your Aws Region')
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('aws_s3_bucket')
            ->cannotBeEmpty()
            ->end()
            ->end();


        return $treeBuilder;
    }
}