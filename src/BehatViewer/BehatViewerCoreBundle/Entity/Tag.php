<?php

namespace BehatViewer\BehatViewerCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerCoreBundle\Entity;

/**
 * BehatViewer\BehatViewerCoreBundle\Entity\Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerCoreBundle\Entity\Repository\TagRepository")
 */
class Tag extends Base
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
     * @ORM\ManyToMany(targetEntity="Feature", mappedBy="tags", cascade={"persist"})
     */
    private $features;

    /**
     * @ORM\ManyToMany(targetEntity="Scenario", mappedBy="tags", cascade={"persist"})
     */
    private $scenarios;

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

    public function __construct()
    {
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scenarios = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add scenarios
     *
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Scenario $scenarios
     */
    public function addScenario(Entity\Scenario $scenarios)
    {
        $this->scenarios[] = $scenarios;
    }

    /**
     * Get scenarios
     *
     * @return \Doctrine\Common\Collections\Collection
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
}
