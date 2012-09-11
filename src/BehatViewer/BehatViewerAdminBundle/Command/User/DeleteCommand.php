<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\User;

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
            ->setName('behat-viewer:user:remove')
            ->addArgument('user', InputArgument::REQUIRED)
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
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('user');
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:User');
        $user = $repository->findOneByUsername($username);

        if (null !== $user) {
            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();

            $output->writeln(sprintf('User <info>%s</info> <comment>(%s)</comment> was successfully deleted', $username, $user->getSalt()));
        } else {
            throw new \RuntimeException(sprintf('user %s does not exist', $username));
        }

        return 0;
    }
}
