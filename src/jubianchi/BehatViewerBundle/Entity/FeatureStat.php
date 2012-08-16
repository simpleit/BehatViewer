<?php

namespace jubianchi\BehatViewerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    jubianchi\BehatViewerBundle\DBAL\Type\EnumStatusType,
    jubianchi\BehatViewerBundle\DBAL\Type\EnumStepStatusType;

/**
 * jubianchi\BehatViewerBundle\Entity\BuildStat
 *
 * @ORM\Table(name="feature_stat")
 * @ORM\Entity(repositoryClass="jubianchi\BehatViewerBundle\Entity\Repository\FeatureStatRepository")
 */
class FeatureStat
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $scenarios
     *
     * @ORM\Column(name="scenarios", type="integer")
     */
    private $scenarios = 0;

    /**
     * @var integer $scenariosPassed
     *
     * @ORM\Column(name="scenarios_passed", type="integer")
     */
    private $scenariosPassed = 0;

    /**
     * @var integer $scenariosFailed
     *
     * @ORM\Column(name="scenarios_failed", type="integer")
     */
    private $scenariosFailed = 0;

    /**
     * @var integer $steps
     *
     * @ORM\Column(name="steps", type="integer")
     */
    private $steps = 0;

    /**
     * @var integer $stepsPassed
     *
     * @ORM\Column(name="steps_passed", type="integer")
     */
    private $stepsPassed = 0;

    /**
     * @var integer $stepsFailed
     *
     * @ORM\Column(name="steps_failed", type="integer")
     */
    private $stepsFailed = 0;

    /**
     * @var integer $stepsPending
     *
     * @ORM\Column(name="steps_pending", type="integer")
     */
    private $stepsPending = 0;

    /**
     * @var integer $stepsSkipped
     *
     * @ORM\Column(name="steps_skipped", type="integer")
     */
    private $stepsSkipped = 0;

    /**
     * @var integer $stepsUndefined
     *
     * @ORM\Column(name="steps_undefined", type="integer")
     */
    private $stepsUndefined = 0;

    /**
     * @var \jubianchi\BehatViewerBundle\Entity\Feature $feature
     *
     * @ORM\OneToOne(targetEntity="Feature", mappedBy="stat", cascade={"persist"})
     */
    private $feature;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set scenarios
     *
     * @param  integer   $scenarios
     * @return BuildStat
     */
    public function setScenarios($scenarios)
    {
        $this->scenarios = $scenarios;

        return $this;
    }

    /**
     * Get scenarios
     *
     * @return integer
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set scenarios_passed
     *
     * @param  integer   $scenariosPassed
     * @return BuildStat
     */
    public function setScenariosPassed($scenariosPassed)
    {
        $this->scenariosPassed = $scenariosPassed;

        return $this;
    }

    /**
     * Get scenarios_passed
     *
     * @return integer
     */
    public function getScenariosPassed()
    {
        return $this->scenariosPassed;
    }

    /**
     * Set scenarios_failed
     *
     * @param  integer   $scenariosFailed
     * @return BuildStat
     */
    public function setScenariosFailed($scenariosFailed)
    {
        $this->scenariosFailed = $scenariosFailed;

        return $this;
    }

    /**
     * Get scenarios_failed
     *
     * @return integer
     */
    public function getScenariosFailed()
    {
        return $this->scenariosFailed;
    }

    /**
     * Set steps
     *
     * @param  integer   $steps
     * @return BuildStat
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * Get steps
     *
     * @return integer
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Set steps_passed
     *
     * @param  integer   $stepsPassed
     * @return BuildStat
     */
    public function setStepsPassed($stepsPassed)
    {
        $this->stepsPassed = $stepsPassed;

        return $this;
    }

    /**
     * Get steps_passed
     *
     * @return integer
     */
    public function getStepsPassed()
    {
        return $this->stepsPassed;
    }

    /**
     * Set steps_failed
     *
     * @param  integer   $stepsFailed
     * @return BuildStat
     */
    public function setStepsFailed($stepsFailed)
    {
        $this->stepsFailed = $stepsFailed;

        return $this;
    }

    /**
     * Get steps_failed
     *
     * @return integer
     */
    public function getStepsFailed()
    {
        return $this->stepsFailed;
    }

    /**
     * Set steps_skipped
     *
     * @param  integer   $stepsSkipped
     * @return BuildStat
     */
    public function setStepsSkipped($stepsSkipped)
    {
        $this->stepsSkipped = $stepsSkipped;

        return $this;
    }

    /**
     * Get steps_skipped
     *
     * @return integer
     */
    public function getStepsSkipped()
    {
        return $this->stepsSkipped;
    }

    /**
     * Set pending steps count
     *
     * @param  integer   $stepsSkipped
     * @return BuildStat
     */
    public function setStepsPending($stepsPending)
    {
        $this->stepsPending = $stepsPending;

        return $this;
    }

    /**
     * Get pending steps count
     *
     * @return integer
     */
    public function getStepsPending()
    {
        return $this->stepsPending;
    }

    /**
     * Set steps_undefined
     *
     * @param  integer   $stepsUndefined
     * @return BuildStat
     */
    public function setStepsUndefined($stepsUndefined)
    {
        $this->stepsUndefined = $stepsUndefined;

        return $this;
    }

    /**
     * Get steps_undefined
     *
     * @return integer
     */
    public function getStepsUndefined()
    {
        return $this->stepsUndefined;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    public function addScenario(Scenario $scenario)
    {
        $this->scenarios++;

        $this->scenariosFailed += $scenario->getStatus() === EnumStatusType::STATUS_FAILED ? 1 : 0;
        $this->scenariosPassed += $scenario->getStatus() === EnumStatusType::STATUS_PASSED ? 1 : 0;

        foreach ($scenario->getSteps() as $step) {
            $this->steps++;

            $this->stepsFailed += $step->getStatus() === EnumStepStatusType::STATUS_FAILED ? 1 : 0;
            $this->stepsPassed += $step->getStatus() === EnumStepStatusType::STATUS_PASSED ? 1 : 0;
            $this->stepsSkipped += $step->getStatus() === EnumStepStatusType::STATUS_SKIPPED ? 1 : 0;
            $this->stepsUndefined += $step->getStatus() === EnumStepStatusType::STATUS_UNDEFINED ? 1 : 0;
        }
    }
}
