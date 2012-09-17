<?php

namespace BehatViewer\BehatViewerCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStatusType,
    BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumStepStatusType,
    BehatViewer\BehatViewerCoreBundle\Entity;

/**
 * BehatViewer\BehatViewerCoreBundle\Entity\BuildStat
 *
 * @ORM\Table(name="configuration")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerCoreBundle\Entity\Repository\ConfigurationRepository")
 */
class Configuration
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
     * @var integer $data
     *
     * @ORM\Column(name="data", type="text")
     */
    private $data;

    /**
     * @var \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     *
     * @ORM\OneToOne(targetEntity="Project", mappedBy="configuration", cascade={"persist"})
     */
    private $project;

    public function getId()
    {
        return $this->id;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setProject(Entity\Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }
}
