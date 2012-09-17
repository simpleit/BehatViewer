<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\User;

use BehatViewer\BehatViewerAdminBundle\Command\Command,
    BehatViewer\BehatViewerBundle\Entity,
	Symfony\Component\Console\Output\OutputInterface,
	Symfony\Component\Console\Input\InputInterface,
	Symfony\Component\Console\Input\InputArgument;

/**
 *
 */
class DeleteCommand extends Command
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
