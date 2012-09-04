<?php
namespace BehatViewer\BehatViewerBundle\Entity;

use
	Doctrine\ORM\Mapping as ORM,
	Symfony\Component\Validator\Constraints as Assert,
	BehatViewer\BehatViewerBundle\Entity\Strategy
;

/**
 * @ORM\Entity
 * @ORM\Table(name="strategy_github")
 */
class GithubStrategy extends Strategy
{
	/**
	 * @var string $username
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="username", type="string", length=50)
	 */
	private $username;

	/**
	 * @var string $repository
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="repository", type="string", length=50)
	 */
	private $repository;

	/**
	 * @var string $branch
	 *
	 * @Assert\NotBlank()
	 * @ORM\Column(name="branch", type="string", length=255)
	 */
	private $branch;

	public function getFormType() {
		return new \BehatViewer\BehatViewerWorkerBundle\Form\Type\GithubStrategyType();
	}

	public function build() {

	}

	public function getUsername() {
		return $this->username;
	}

	public function getRepository() {
		return $this->repository;
	}

	public function getBranch() {
		return $this->branch;
	}

	public function getUrl() {
		return sprintf(
			'git://github.com/%s/%s',
			$this->getUsername(),
			$this->getRepository()
		);
	}

	public function __toString() {
		return 'Github';
	}
}
