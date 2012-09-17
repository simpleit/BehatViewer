<?php
namespace BehatViewer\BehatViewerPusherBundle\Command;

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
class TestCommand extends \BehatViewer\BehatViewerAdminBundle\Command\Command
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('behat-viewer:pusher:test')
            ->setDescription('Sends dummy content through pusher')
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
        $output = $this->getContainer()->get('behat_viewer.pusher.output');

        for ($i = 0, $max = rand(10, 30); $i < $max; $i++) {
            if (rand(1, 10) < 5) {
                $msg = 'This is a standard message';
            } else {
                $fg = rand(30, 37);
                $bg = rand(0, 5);
                $opts = array(1, 4, 5, 7);
                $opt = rand(0, count($opts) - 1);

                $msg = sprintf(
                    "\033[%d;%d;%dmThis is a styled message\033[0m",
                    $fg,
                    $bg,
                    $opts[$opt]
                );
            }

            $output->write($msg);
            usleep(rand(250, 750));
        }
    }
}
