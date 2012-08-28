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
            ->setDescription('Generate a ssh key for a given project')
            ->setDefinition(
                array(
                    new InputArgument('username', InputArgument::REQUIRED, 'Username'),
                    new InputArgument('project', InputArgument::REQUIRED, 'Project identifier'),
                    new InputArgument('owner', InputArgument::OPTIONAL, 'WWW user', 'www-data')
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
        $dir = $this->getContainer()->get('kernel')->getRootDir() . '/data/keys';
        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = sprintf(
            $dir . '/%s-%s',
            $input->getArgument('username'),
            $input->getArgument('project')
        );

        passthru(
            sprintf(
                'sudo -u %s ssh-keygen -t rsa -f %s',
                $input->getArgument('owner'),
                $path
            )
        );

        if (file_exists($path)) {
            $output->write(file_get_contents($path . '.pub'));
        }
    }
}
