<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(\Symfony\Component\DependencyInjection\ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*.yaml', 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/*.yaml', 'glob');
        $loader->load($confDir.'/{services}.yaml', 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.'.yaml', 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*.yaml', '/', 'glob');
        $routes->import($confDir.'/{routes}/'.$this->environment.'/*.yaml', '/', 'glob');
        $routes->import($confDir.'/routes.yaml');
    }
}
