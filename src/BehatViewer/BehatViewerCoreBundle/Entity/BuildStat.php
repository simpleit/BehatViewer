<?php

namespace BehatViewer\BehatViewerCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStatusType,
    BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStepStatusType;

/**
 * BehatViewer\BehatViewerCoreBundle\Entity\BuildStat
 *
 * @ORM\Table(name="build_stat")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerCoreBundle\Entity\Repository\BuildStatRepository")
 */
class BuildStat
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
     * @var integer $features
     *
     * @ORM\Column(name="features", type="integer")
     */
    private $features = 0;

    /**
     * @var integer $features_passed
     *
     * @ORM\Column(name="features_passed", type="integer")
     */
    private $featuresPassed = 0;

    /**
     * @var integer $features_failed
     *
     * @ORM\Column(name="features_failed", type="integer")
     */
    private $featuresFailed = 0;

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
     * @var \BehatViewer\BehatViewerCoreBundle\Entity\Build $build
     *
     * @ORM\OneToOne(targetEntity="Build", mappedBy="stat", cascade={"persist"})
     */
    private $build;

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
     * Set features
     *
     * @param  integer   $features
     * @return BuildStat
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features
     *
     * @return integer
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set features_passed
     *
     * @param  integer   $featuresPassed
     * @return BuildStat
     */
    public function setFeaturesPassed($featuresPassed)
    {
        $this->featuresPassed = $featuresPassed;

        return $this;
    }

    /**
     * Get features_passed
     *
     * @return integer
     */
    public function getFeaturesPassed()
    {
        return $this->featuresPassed;
    }

    /**
     * Set features_failed
     *
     * @param  integer   $featuresFailed
     * @return BuildStat
     */
    public function setFeaturesFailed($featuresFailed)
    {
        $this->featuresFailed = $featuresFailed;

        return $this;
    }

    /**
     * Get features_failed
     *
     * @return integer
     */
    public function getFeaturesFailed()
    {
        return $this->featuresFailed;
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

    public function getBuild()
    {
        return $this->build;
    }

    public function setBuild(Build $build)
    {
        $this->build = $build;

        return $this;
    }

    public function addFeature(Feature $feature)
    {
        $this->features += 1;
        if ($feature->getStatus() === EnumStatusType::STATUS_PASSED) {
            $this->featuresPassed += 1;
        } else {
            $this->featuresFailed += 1;
        }

        foreach ($feature->getScenarios() as $scenario) {
            $this->scenarios ++;
            $this->scenariosFailed += $scenario->getStatus() === EnumStatusType::STATUS_FAILED ? 1 : 0;
            $this->scenariosPassed += $scenario->getStatus() === EnumStatusType::STATUS_PASSED ? 1 : 0;

            $this->steps += count($scenario->getSteps());

            $this->stepsFailed += $scenario->getStepsHavingStatusCount(EnumStepStatusType::STATUS_FAILED);
            $this->stepsPassed += $scenario->getStepsHavingStatusCount(EnumStepStatusType::STATUS_PASSED);
            $this->stepsSkipped += $scenario->getStepsHavingStatusCount(EnumStepStatusType::STATUS_SKIPPED);
            $this->stepsUndefined += $scenario->getStepsHavingStatusCount(EnumStepStatusType::STATUS_UNDEFINED);
        }
    }
}
