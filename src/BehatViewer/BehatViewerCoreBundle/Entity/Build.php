<?php

namespace BehatViewer\BehatViewerCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\Entity;

/**
 * BehatViewer\BehatViewerCoreBundle\Entity\Build
 *
 * @ORM\Table(name="build")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerCoreBundle\Entity\Repository\BuildRepository")
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
     * @var \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="builds")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var \BehatViewer\BehatViewerCoreBundle\Entity\BuildStat $stat
     *
     * @ORM\OneToOne(targetEntity="BuildStat", inversedBy="build", cascade={"persist", "remove"})
     */
    private $stat;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="status")
     */
    private $status;

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
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     */
    public function setProject(Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return \BehatViewer\BehatViewerCoreBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add features
     *
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Feature $features
     */
    public function addFeature(Entity\Feature $features)
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
            $feature->computeStat();
            $stat->addFeature($feature);
        }
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
