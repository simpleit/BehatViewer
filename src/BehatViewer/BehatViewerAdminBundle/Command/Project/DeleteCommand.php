<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\Project;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\EventDispatcher\EventSubscriberInterface,
    Symfony\Component\EventDispatcher\Event,
    Symfony\Component\Console\Formatter\OutputFormatterStyle,
    Symfony\Component\Console\Input\InputOption,
    BehatViewer\BehatViewerBundle\Entity;

/**
 *
 */
class DeleteCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('behat-viewer:project:delete')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('project', InputArgument::REQUIRED)
        ;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 *
	 * @throws \RuntimeException
	 *
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $slug = $input->getArgument('project');

        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');
        $project = $repository->findOneByUsernameAndSlug($username, $slug);

        if (null !== $project) {
            $this->getEntityManager()->remove($project);
            $this->getEntityManager()->flush();

            $output->writeln(sprintf('Project <info>%s/%s</info> was successfully deleted', $username, $slug));
        } else {
            throw new \RuntimeException(sprintf('Project %s/%s does not exist', $username, $slug));
        }

		return 0;
    }
}
