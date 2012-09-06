<?php
namespace BehatViewer\BehatViewerBundle\Entity;

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
	 * @ORM\Column(name="path", type="string", length=255, nullable=true)
	 */
	private $path;

	/**
	 * @var string $branch
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="branch", type="string", length=255, nullable=true)
	 */
	private $branch;

	public function getFormType() {
		return new \BehatViewer\BehatViewerBundle\Form\Type\GitLocalStrategyType();
	}

	public function build() {

	}

	public function setPath($path) {
		$this->path = $path;

		return $this;
	}

	public function getPath() {
		return $this->path;
	}

	public function getBranch() {
		return $this->branch;
	}

	public function setBranch($branch) {
		$this->branch = $branch;

		return $this;
	}

	public function __toString() {
		return 'Local Git repository';
	}
}
