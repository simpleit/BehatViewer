<?php

namespace BehatViewer\BehatViewerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerBundle\Entity\Project;

/**
 * BehatViewer\BehatViewerBundle\Entity\BuildStat
 *
 * @ORM\Table(name="strategy")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerBundle\Entity\Repository\StrategyRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 * 		"local" = "BehatViewer\BehatViewerBundle\Entity\LocalStrategy",
 * 		"git" = "BehatViewer\BehatViewerBundle\Entity\GitStrategy",
 * 		"git_local" = "BehatViewer\BehatViewerBundle\Entity\GitLocalStrategy",
 * 		"github" = "BehatViewer\BehatViewerBundle\Entity\GithubStrategy"
 * })
 */
abstract class Strategy extends Base
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
     * @var \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @ORM\OneToOne(targetEntity="Project", mappedBy="strategy", cascade={"persist"})
     */
    private $project;

    public function getId()
    {
        return $this->id;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }

    abstract public function getFormType();
    abstract public function build();
}
