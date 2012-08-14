<?php

namespace jubianchi\BehatViewerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * jubianchi\BehatViewerBundle\Entity\Feature
 *
 * @ORM\Table(name="feature")
 * @ORM\Entity(repositoryClass="jubianchi\BehatViewerBundle\Entity\Repository\FeatureRepository")
 */
class Feature extends Base
{
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \jubianchi\BehatViewerBundle\Entity\Build $build
     *
     * @ORM\ManyToOne(targetEntity="Build", inversedBy="features", cascade={"persist"})
     * @ORM\JoinColumn(name="build_id", referencedColumnName="id")
     */
    private $build;

    /**
     * @ORM\OneToMany(targetEntity="Scenario", mappedBy="feature", cascade={"remove", "persist"})
     */
    private $scenarios;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="features", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="feature_tags")
     */
    private $tags;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

    /**
     * @var \jubianchi\BehatViewerBundle\Entity\FeatureStat $stat
     *
     * @ORM\OneToOne(targetEntity="FeatureStat", inversedBy="feature", cascade={"persist", "remove"})
     */
    private $stat;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function __construct()
    {
        $this->scenarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add scenarios
     *
     * @param jubianchi\BehatViewerBundle\Entity\Scenario $scenarios
     */
    public function addScenario(\jubianchi\BehatViewerBundle\Entity\Scenario $scenarios)
    {
        $this->scenarios[] = $scenarios;
    }

    /**
     * Get scenarios
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add tag
     *
     * @param \jubianchi\BehatViewerBundle\Entity\Tag $tag
     */
    public function addTag(\jubianchi\BehatViewerBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * Add tags
     *
     * @param \jubianchi\BehatViewerBundle\Entity\Tag $tags
     */
    public function addTags(array $tags)
    {
        $this->tags = array_merge($tags, (array) $this->tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getFile()
    {
        $scenarios = $this->getScenarios();
        $steps = $scenarios[0]->getSteps();

        return $steps[0]->getFile();
    }

    /**
     * Add builds
     *
     * @param \jubianchi\BehatViewerBundle\Entity\Build $builds
     */
    public function setBuild(\jubianchi\BehatViewerBundle\Entity\Build $build)
    {
        $this->build = $build;
    }

    /**
     * Get builds
     *
     * @return \jubianchi\BehatViewerBundle\Entity\Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    public function getStat()
    {
        if (null === $this->stat) {
            $stat = new FeatureStat();
            $stat->setFeature($this);

            $this->setStat($stat);
        }

        return $this->stat;
    }

    public function setStat(FeatureStat $stat)
    {
        $this->stat = $stat;
    }

    public function computeStat()
    {
        $stat = $this->getStat();

        foreach ($this->getScenarios() as $scenario) {
            $stat->addScenario($scenario);
        }
    }
}
