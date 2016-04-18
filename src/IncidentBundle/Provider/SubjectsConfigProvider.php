<?php

namespace IncidentBundle\Provider;

/**
 * Class SubjectsConfigProvider
 */
class SubjectsConfigProvider
{
    /**
     * @var array
     */
    private $subjects = [];

    /**
     * SubjectsConfigProvider constructor.
     *
     * @param array $subjectsConfig
     */
    public function __construct($subjectsConfig)
    {
        foreach ($subjectsConfig as $subjectsType => $subjectsByTypeArray) {
            if (is_array($subjectsByTypeArray)) {
                foreach ($subjectsByTypeArray as $subjectId => $subjectItem) {
                    $this->subjects[$subjectId] = $subjectItem;
                    $this->subjects[$subjectId]['type'] = $subjectsType;
                }
            }
        }
    }

    /**
     * @param string $subjectsType
     *
     * @return array
     */
    public function getByType($subjectsType)
    {
        $retSubjects = [];

        foreach ($this->subjects as $subjectId => $subjectItem) {
            if (!isset($subjectItem['type']) || $subjectItem['type'] != $subjectsType) {
                continue;
            }

            $retSubjects[$subjectId] = $subjectItem;
        }

        return $retSubjects;
    }
}
