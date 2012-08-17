<?php
namespace BehatViewer\BehatViewerBundle\Command;

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
            ->setName('behat-viewer:clean')
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

          $output->writeln(sprintf('<info>[INFO]</info> Cleaning outdated builds for project <comment>%s</comment>', $project->getSlug()));

        $repository = $this->getContainer()->get('doctrine')->getRepository('BehatViewerBundle:Build');
        $count = $repository->removeWeekBuildsForProject($project);

        $output->writeln(sprintf('<info>[INFO]</info> Deleted <comment>%d</comment> build(s)', $count));
    }
}
