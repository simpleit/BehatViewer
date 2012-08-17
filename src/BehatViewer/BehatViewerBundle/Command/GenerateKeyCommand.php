<?php
namespace BehatViewer\BehatViewerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument;

class GenerateKeyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('behat-viewer:generate-key')
            ->setDescription('Generate a ssh key for Behat Viewer')
            ->setDefinition(
                array(
                    new InputArgument('user', InputArgument::OPTIONAL, 'WWW user', 'www-data')
                )
            )
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->get('kernel')->getRootDir() . '/data/id_rsa';
        passthru(
            sprintf(
                'sudo -u %s ssh-keygen -t rsa -f %s',
                $input->getArgument('user'),
                $path
            )
        );

        if (file_exists($path)) {
            $output->write(file_get_contents($path . '.pub'));
        }
    }
}
