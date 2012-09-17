<?php
namespace BehatViewer\BehatViewerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use BehatViewer\BehatViewerBundle\Entity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRolesData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
		foreach($this->getRoles() as $key => $name) {
			$role = new Entity\Role();
			$role->setName($name);
			$role->setRole($key);
			$manager->persist($role);

			$this->addReference($key, $role);
		}

        $manager->flush();
    }

	protected function getRoles() {
		return array(
			'ROLE_USER' => 'User',
			'ROLE_ADMIN' => 'Admin',
			'ROLE_PREMIUM' => 'Premium'
		);
	}

    public function getOrder()
    {
        return 1;
    }
}
