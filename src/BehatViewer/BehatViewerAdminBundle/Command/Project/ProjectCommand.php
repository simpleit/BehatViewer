<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\Project;

use BehatViewer\BehatViewerAdminBundle\Command\Command,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface;

abstract class ProjectCommand extends Command
{
    /**
     * @var \BehatViewer\BehatViewerBundle\Entity\Project
     */
    private $project;

    protected function configure()
    {
        $this
			->addArgument('username', InputArgument::REQUIRED, 'The owner username')
			->addArgument('project', InputArgument::REQUIRED, 'The project identifier')
		;
    }

    protected function validate(InputInterface $input)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');

        $this->project = $repository->findOneByUsernameAndSlug(
			$input->getArgument('username'),
			$input->getArgument('project')
		);

        if (null === $this->project) {
            throw new \InvalidArgumentException('Unknown project ' . $input->getArgument('project'));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validate($input);
    }

    /**
     * @return \BehatViewer\BehatViewerBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
