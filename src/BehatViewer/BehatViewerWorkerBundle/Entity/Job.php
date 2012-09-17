<?php

namespace BehatViewer\BehatViewerWorkerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\Entity;

/**
 * BehatViewer\BehatViewerWorkerBundle\Entity\Job
 *
 * @ORM\Table(name="job")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerWorkerBundle\Entity\Repository\JobRepository")
 */
class Job extends Entity\Base
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
     * @ORM\ManyToOne(targetEntity="BehatViewer\BehatViewerCoreBundle\Entity\Project", inversedBy="builds")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="job_status", nullable=true)
     */
    private $status;

    /**
     * @var string $log
     *
     * @ORM\Column(name="log", type="text", nullable=true)
     */
    private $log;

    public function getId()
    {
        return $this->id;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function addLog($log)
    {
        $this->log .= $log;

        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }

<<<<<<< HEAD
    public function setProject(Project $project)
=======
    public function setProject(Entity\Project $project)
>>>>>>> Moving code to Core bundle
    {
        $this->project = $project;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
