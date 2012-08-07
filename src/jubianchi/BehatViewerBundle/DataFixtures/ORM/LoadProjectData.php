<?php
namespace jubianchi\BehatViewerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use jubianchi\BehatViewerBundle\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $project = new Project();
        $project->setName('Behat Viewer');
        $project->setSlug('behat-viewer');
        $project->setBaseUrl('http://behat-viewer.local');
        $project->setRootPath(realpath($this->container->get('kernel')->getRootDir() . '/..'));
        $project->setOutputPath(realpath($this->container->get('kernel')->getRootDir() . '/..'));
        $project->setTestCommand(<<<SCRIPT
app/console --env=test doctrine:database:drop --force
app/console --env=test doctrine:database:create

app/console --env=test doctrine:schema:create
app/console --env=test cache:clear
app/console assets:install --symlink web

bin/behat @BehatViewerBundle
SCRIPT
);
        $project->setUser($this->getReference('admin-user'));

        $manager->persist($project);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
