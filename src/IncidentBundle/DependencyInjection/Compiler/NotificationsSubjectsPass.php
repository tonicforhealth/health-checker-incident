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

use IncidentBundle\Provider\SubjectsConfigProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class for NotificationsSubjectsPass
 */
class NotificationsSubjectsPass implements CompilerPassInterface
{
    const SUBJECTS_ID = 'incident.siren.notification.subjects.%s.%s';
    const SCHEDULE_ID = 'incident.siren.notification.schedule.%s.%s';
    const INCIDENT_SCHEDULE_PROTOTYPE = 'incident.schedule_prototype';
    const INCIDENT_SUBJECT_PROTOTYPE = 'incident.subject_prototype';
    const INCIDENT_NOTIFICATIONS_SUBJECTS = 'incident.notifications.subjects';
    const INCIDENT_SIREN_NOTIFICATION_SUBJECTS = 'incident.siren.notification.subjects';

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var SubjectsConfigProvider
     */
    private $subjectsProvider;

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->setContainer($container);

        if (!$this->getContainer()->hasParameter(self::INCIDENT_NOTIFICATIONS_SUBJECTS)) {
            return;
        }

        $subjectsConfig = $this->getContainer()->getParameter(
            self::INCIDENT_NOTIFICATIONS_SUBJECTS
        );

        $this->setSubjectsProvider(new SubjectsConfigProvider($subjectsConfig));

        $taggedServices = $this->getContainer()->findTaggedServiceIds(
            self::INCIDENT_SIREN_NOTIFICATION_SUBJECTS
        );

        $this->processNotificationsTypes($taggedServices);
    }

    /**
     * @param $taggedServices
     */
    protected function processNotificationsTypes($taggedServices)
    {
        foreach ($taggedServices as $notSubjectsId => $tags) {
            if (!isset($tags[0]['type_name'])) {
                continue;
            }
            $typeName = $tags[0]['type_name'];

            $subjects = $this->getSubjectsProvider()->getByType($typeName);
            $this->processSubjects($subjects, $typeName, $notSubjectsId);
        }
    }

    /**
     * @param array  $subjects
     * @param string $typeName
     * @param string $notSubjectsId
     */
    protected function processSubjects($subjects, $typeName, $notSubjectsId)
    {
        foreach ($subjects as $subjectsId => $subjectsItem) {

            $subjectDef = $this->createSubjectDef($typeName, $subjectsItem, $subjectsId);
            $subjectId = sprintf(self::SUBJECTS_ID, $typeName, $subjectsId);
            $this->getContainer()->setDefinition($subjectId, $subjectDef);
            $notSubjectsDef = $this->getContainer()->getDefinition($notSubjectsId);

            $notSubjectsDef->addMethodCall(
                'add',
                [new Reference($subjectId)]
            );
        }
    }

    /**
     * @param string $schedule
     * @return DefinitionDecorator
     */
    protected function createScheduleDef($schedule)
    {
        $scheduleDef = new DefinitionDecorator(self::INCIDENT_SCHEDULE_PROTOTYPE);
        $scheduleDef->replaceArgument(0, $schedule);

        return $scheduleDef;
    }

    /**
     * @param string $typeName
     * @param array  $subjectsItem
     * @param string $subjectsId
     * @return DefinitionDecorator
     */
    protected function createSubjectDef($typeName, $subjectsItem, $subjectsId)
    {
        $subjectDef = new DefinitionDecorator(self::INCIDENT_SUBJECT_PROTOTYPE);
        if (isset($subjectsItem['target'])) {
            $subjectDef->replaceArgument(0, $subjectsItem['target']);
        }

        if (isset($subjectsItem['schedule'])) {
            $scheduleDef = $this->createScheduleDef($subjectsItem['schedule']);
            $scheduleId = sprintf(self::SCHEDULE_ID, $typeName, $subjectsId);
            $this->getContainer()->setDefinition($scheduleId, $scheduleDef);
            $subjectDef->replaceArgument(1, new Reference($scheduleId));
        }

        return $subjectDef;
    }

    /**
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    protected function getSubjectsProvider()
    {
        return $this->subjectsProvider;
    }

    /**
     * @param ContainerBuilder $container
     */
    private function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $subjectsProvider
     */
    private function setSubjectsProvider($subjectsProvider)
    {
        $this->subjectsProvider = $subjectsProvider;
    }
}
