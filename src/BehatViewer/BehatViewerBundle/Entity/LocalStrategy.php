<?php
namespace BehatViewer\BehatViewerBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	BehatViewer\BehatViewerBundle\Entity\Strategy
;

/**
 * @ORM\Entity
 * @ORM\Table(name="strategy_local")
 */
class LocalStrategy extends Strategy
{
	/**
	 * @var string $path
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="path", type="string", length=255, nullable=true)
	 */
	private $path;

	public function getFormType() {
		return new \BehatViewer\BehatViewerBundle\Form\Type\LocalStrategyType();
	}

	public function build() {

	}

	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		$this->path = $path;

		return $this;
	}

	public function __toString() {
		return 'Local directory';
	}
}
