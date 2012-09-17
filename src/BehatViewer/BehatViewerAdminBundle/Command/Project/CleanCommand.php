<?php
namespace BehatViewer\BehatViewerAdminBundle\Command\Project;

use Symfony\Component\Console\Input\InputInterface,
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
class CleanCommand extends ProjectCommand
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('behat-viewer:project:clean')
            ->setDescription('Cleans outdated builds')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $project = $this->getProject();

        $this->log(
            $output,
            sprintf(
                '<info>[INFO]</info> Cleaning outdated builds for project <comment>%s</comment>',
                $project->getSlug()
            )
        );

        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $count = $repository->removeWeekBuildsForProject($project);

        $this->log(
            $output,
            sprintf(
                '<info>[INFO]</info> Deleted <comment>%d</comment> build(s)',
                $count
            )
        );
    }
}
