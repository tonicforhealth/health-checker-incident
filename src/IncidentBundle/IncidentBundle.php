<?php

namespace IncidentBundle;

use IncidentBundle\DependencyInjection\Compiler\NotificationsSubjectsPass;
use IncidentBundle\DependencyInjection\Compiler\SirenCollectionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IncidentBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SirenCollectionPass());
        $container->addCompilerPass(new NotificationsSubjectsPass());
    }
}
