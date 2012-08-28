<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration;

/**
 * @Route("/help")
 */
class HelpController extends BehatViewerController
{
    /**
     * @param string $section
     * @param string $page
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     *
     * @Configuration\Route("/{section}/{page}", name="behatviewer.help", defaults={"section" = "", "page" = "home"})
     * @Configuration\Template()
     */
    public function indexAction($section, $page)
    {
        $section = $section ? $section . '/' : '';
        $file = $this->getDataDirectory() . '/' . $section . $page . '.md';
        if (false === file_exists($file)) {
            throw $this->createNotFoundException();
        }

        return $this->getResponse(array(
            'help' => file_get_contents($file),
            'sections' => $this->getSections()
        ));
    }

    /**
     * @return string
     */
    protected function getDataDirectory()
    {
        return __DIR__ . '/../Resources/doc/help';
    }

    /**
     * @return array
     */
    protected function getSections()
    {
        $content = new \RecursiveDirectoryIterator($this->getDataDirectory(), \FilesystemIterator::SKIP_DOTS);
        $sections = array();

        foreach ($content as $directory) {
            if ($directory->isDir()) {
                $section = basename($directory);
                $sections[$section] = array(
                    'label' => preg_replace('/\d+[\.|\-]/', '', $section),
                    'links' => array()
                );

                $iterator = new \RecursiveRegexIterator(
                    new \RecursiveDirectoryIterator($directory->getPathname()),
                    '/[a-z]*.md$/i'
                );

                foreach ($iterator as $file) {
                    $file = basename($file, '.md');
                    $label = preg_replace('/\d+[\.|\-]/', '', $file);
                    $label = str_replace('-', ' ', $label);
                    $label = ucfirst($label);
                    $sections[$section]['links'][$label] = $file;
                }
            }
        }

        return $sections;
    }
}
