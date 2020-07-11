<?php


namespace TheCodingMachine\TDBM\Bundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class TdbmExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param mixed[] $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //$config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/container'));
        $loader->load('tdbm.xml');

        $definition = $container->getDefinition(\TheCodingMachine\TDBM\Configuration::class);
        $definition->replaceArgument(0, $config['bean_namespace']);
        $definition->replaceArgument(1, $config['dao_namespace']);

        if (isset($config['naming'])) {
            $definitionNamingStrategy = $container->getDefinition(\TheCodingMachine\TDBM\Utils\DefaultNamingStrategy::class);
            $definitionNamingStrategy->addMethodCall('setBeanPrefix', [$config['naming']['bean_prefix']]);
            $definitionNamingStrategy->addMethodCall('setBeanSuffix', [$config['naming']['bean_suffix']]);
            $definitionNamingStrategy->addMethodCall('setBaseBeanPrefix', [$config['naming']['base_bean_prefix']]);
            $definitionNamingStrategy->addMethodCall('setBaseBeanSuffix', [$config['naming']['base_bean_suffix']]);
            $definitionNamingStrategy->addMethodCall('setDaoPrefix', [$config['naming']['dao_prefix']]);
            $definitionNamingStrategy->addMethodCall('setDaoSuffix', [$config['naming']['dao_suffix']]);
            $definitionNamingStrategy->addMethodCall('setBaseDaoPrefix', [$config['naming']['base_dao_prefix']]);
            $definitionNamingStrategy->addMethodCall('setBaseDaoSuffix', [$config['naming']['base_dao_suffix']]);
            $definitionNamingStrategy->addMethodCall('setExceptions', [$config['naming']['exceptions']]);
        }
    }
}
