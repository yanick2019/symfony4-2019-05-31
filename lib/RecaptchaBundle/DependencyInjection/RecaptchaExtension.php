<?php


namespace MyLib\RecaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

# use MyLib\RecaptchaBundle\DependencyInjection\Configuration;

class RecaptchaExtension extends Extension  {

    /**
     * Loads a specific configuration 
     * 
     * @throws \InvalidArgumentException When proviced tag is not defined in this extension
     */
    public function load(array $configs , ContainerBuilder $container ){
        
        # $configs 是 config\packages\recaptcha.yaml 中定义的参数[key,secret]

        # 读取 lib\RecaptchaBundle\Resources\config\services.yaml 
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config' )
         ) ;

        $loader->load('services.yaml') ; 


        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration,$configs);
        $container->setParameter('recaptcha.key' , $config['key']);
        $container->setParameter('recaptcha.secret' , $config['secret']);
        # $ php bin/console debug:container --parameter=recaptcha.key
        
    }
}