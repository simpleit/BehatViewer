<?php
namespace BehatViewer\BehatViewerBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	BehatViewer\BehatViewerBundle\Entity\Strategy
;

/**
 * @ORM\Entity
 * @ORM\Table(name="strategy_git")
 */
class GitStrategy extends Strategy
{
	/**
	 * @var string $url
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="url", type="string", length=255)
	 */
	private $url;

	/**
	 * @var string $branch
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="branch", type="string", length=255)
	 */
	private $branch;

	public function getFormType() {
		return new \BehatViewer\BehatViewerWorkerBundle\Form\Type\GitStrategyType();
	}

	public function build() {

	}

	public function getUrl() {
		return $this->url;
	}

	public function getBranch() {
		return $this->branch;
	}

	public function __toString() {
		return 'Git repository';
	}
}
