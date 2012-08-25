<?php

namespace BehatViewer\BehatViewerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    BehatViewer\BehatViewerBundle\DBAL\Type\EnumStatusType,
    BehatViewer\BehatViewerBundle\DBAL\Type\EnumStepStatusType;

/**
 * BehatViewer\BehatViewerBundle\Entity\BuildStat
 *
 * @ORM\Table(name="configuration")
 * @ORM\Entity(repositoryClass="BehatViewer\BehatViewerBundle\Entity\Repository\ConfigurationRepository")
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
	 * @var \BehatViewer\BehatViewerBundle\Entity\Project $project
	 *
	 * @ORM\OneToOne(targetEntity="Project", mappedBy="configuration", cascade={"persist"})
	 */
	private $project;

	public function getId() {
		return $this->id;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}

	public function setProject(\BehatViewer\BehatViewerBundle\Entity\Project $project)
	{
		$this->project = $project;
	}

	public function getProject()
	{
		return $this->project;
	}

	public function __set($name, $value) {
		$params = json_decode($this->data, true);

		$params[$name] = $value;

		$this->setData(json_encode($params));
	}

	public function __get($name) {
		$params = json_decode($this->data);

		return $params[$name];
	}
}
