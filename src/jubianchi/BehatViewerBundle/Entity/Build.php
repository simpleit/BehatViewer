<?php

namespace jubianchi\BehatViewerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * jubianchi\BehatViewerBundle\Entity\Build
 *
 * @ORM\Table(name="build")
 * @ORM\Entity(repositoryClass="jubianchi\BehatViewerBundle\Entity\Repository\BuildRepository")
 */
class Build extends Base
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
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \jubianchi\BehatViewerBundle\Entity\Project $project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="builds")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var \jubianchi\BehatViewerBundle\Entity\BuildStat $stat
     *
     * @ORM\OneToOne(targetEntity="BuildStat", inversedBy="build", cascade={"persist", "remove"})
     */
    private $stat;

    /**
     * @ORM\OneToMany(targetEntity="Feature", mappedBy="build", cascade={"remove","persist"})
     */
    private $features;

    public function __construct()
    {
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set project
     *
     * @param \jubianchi\BehatViewerBundle\Entity\Project $project
     */
    public function setProject(\jubianchi\BehatViewerBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return \jubianchi\BehatViewerBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add features
     *
     * @param \jubianchi\BehatViewerBundle\Entity\Feature $features
     */
    public function addFeature(\jubianchi\BehatViewerBundle\Entity\Feature $features)
    {
        $this->features[] = $features;
    }

    /**
     * Get features
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    public function getStat()
    {
        if (null === $this->stat) {
            $stat = new BuildStat();
            $stat->setBuild($this);

            $this->setStat($stat);
        }

        return $this->stat;
    }

    public function setStat(BuildStat $stat)
    {
        $this->stat = $stat;
    }

    public function computeStat()
    {
        $stat = $this->getStat();

        foreach ($this->getFeatures() as $feature) {
            $stat->addFeature($feature);
        }
    }
}
