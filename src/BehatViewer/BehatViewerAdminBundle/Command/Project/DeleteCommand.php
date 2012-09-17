<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\Project;

use Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument;

/**
 *
 */
class DeleteCommand extends ProjectCommand
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('behat-viewer:project:delete');
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
        parent::execute($input, $output);

        $username = $input->getArgument('username');
        $slug = $input->getArgument('project');
        $project = $this->getProject();

        $this->getEntityManager()->remove($project);
        $this->getEntityManager()->flush();

        $output->writeln(sprintf('Project <info>%s/%s</info> was successfully deleted', $username, $slug));

        return 0;
    }
}
