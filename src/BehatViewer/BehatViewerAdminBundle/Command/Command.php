<?php
namespace BehatViewer\BehatViewerAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends ContainerAwareCommand
{
    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

	/**
	 * @param string $name
	 *
	 * @return \Doctrine\Common\Persistence\ObjectManager|object
	 */
	public function getEntityManager($name = null)
	{
		return $this->getDoctrine()->getManager($name);
	}

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @param string $message
	 */
	protected function log(OutputInterface $output, $message)
    {
        if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
            $output->writeln($this->formatLog($message));
        }
    }

	/**
	 * @param string $message
	 *
	 * @return string
	 */
	protected function formatLog($message)
    {
        return sprintf(
            '<info>[INFO]</info> %s',
            $message
        );
    }
}
