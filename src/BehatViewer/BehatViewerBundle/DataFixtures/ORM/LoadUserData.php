<?php
namespace BehatViewer\BehatViewerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use BehatViewer\BehatViewerBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('password');

        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($admin);

        $admin->setPassword(
            $encoder->encodePassword(
                'password',
                $admin->getSalt()
            )
        );

        $admin->setEmail('');

        $manager->persist($admin);
        $manager->flush();

        $this->addReference('admin-user', $admin);
    }

    public function getOrder()
    {
        return 1;
    }
}
