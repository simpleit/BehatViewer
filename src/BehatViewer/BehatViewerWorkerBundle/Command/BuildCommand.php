<?php
namespace BehatViewer\BehatViewerWorkerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    BehatViewer\BehatViewerBundle\Command\ProjectCommand;

/**
 *
 */
class BuildCommand extends ProjectCommand
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('behat-viewer:build')
            ->setDescription('Builds a project\'s report file')
            ->addOption('definitions', null, InputOption::VALUE_NONE, 'Updates definition list')
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

		$provider = $this->getContainer()->get('behat_viewer.strategy.provider');
        $strategy = $provider->getStrategyForProject($this->getProject());
        $strategy->setOutput($output);

        return $strategy->build();
    }
}
