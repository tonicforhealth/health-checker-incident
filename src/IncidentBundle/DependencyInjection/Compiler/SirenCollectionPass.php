<?php

/*
 * This file is part of the Doctrine Bundle
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project, Benjamin Eberlei <kontakt@beberlei.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IncidentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class for SirenCollection
 */
class SirenCollectionPass implements CompilerPassInterface
{
    const INCIDENT_SIREN_NOTIFICATION = 'incident.siren.notification';
    const INCIDENT_SIREN = 'incident.siren';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::INCIDENT_SIREN)) {
            return;
        }

        $sirenDefinition = $container->getDefinition(
            self::INCIDENT_SIREN
        );

        $taggedServices = $container->findTaggedServiceIds(
            self::INCIDENT_SIREN_NOTIFICATION
        );

        foreach ($taggedServices as $id => $tags) {
            $sirenDefinition->addMethodCall(
                    'add',
                    [new Reference($id)]
                );
        }
    }
}
