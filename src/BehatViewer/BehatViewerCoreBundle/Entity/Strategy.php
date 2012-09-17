<?php

namespace BehatViewer\BehatViewerCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\Entity\Project;

/**
 * BehatViewer\BehatViewerCoreBundle\Entity\BuildStat
 *
 * @ORM\Table(name="strategy")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerCoreBundle\Entity\Repository\StrategyRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *         "local" = "BehatViewer\BehatViewerCoreBundle\Entity\LocalStrategy",
 *         "git" = "BehatViewer\BehatViewerCoreBundle\Entity\GitStrategy",
 *         "git_local" = "BehatViewer\BehatViewerCoreBundle\Entity\GitLocalStrategy",
 *         "github" = "BehatViewer\BehatViewerCoreBundle\Entity\GithubStrategy"
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
     * @var \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
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
