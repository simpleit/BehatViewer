<?php
namespace BehatViewer\BehatViewerWorkerBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	BehatViewer\BehatViewerBundle\Entity\Strategy
;

/**
 * @ORM\Entity
 * @ORM\Table(name="strategy_git_local")
 */
class GitLocalStrategy extends Strategy
{
	/**
	 * @var string $path
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="path", type="string", length=255)
	 */
	private $path;

	/**
	 * @var string $branch
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="branch", type="string", length=255)
	 */
	private $branch;

	public function getFormType() {
		return new \BehatViewer\BehatViewerWorkerBundle\Form\Type\GitLocalStrategyType();
	}

	public function build() {

	}

	public function getPath() {
		return $this->path;
	}

	public function getBranch() {
		return $this->branch;
	}

	public function __toString() {
		return 'Local Git repository';
	}
}
