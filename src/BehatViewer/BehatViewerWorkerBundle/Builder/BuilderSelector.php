<?php
namespace BehatViewer\BehatViewerWorkerBundle\Builder;

use
    Symfony\Component\DependencyInjection\ContainerAware,
    BehatViewer\BehatViewerBundle\Entity,
    Symfony\Component\Console\Output\ConsoleOutput
;

class BuilderSelector extends Builder
{
    /** @var Builder[] */
    private $builders = array();

    /**
     * @abstract
     *
     * @return int
     */
    public function build(Entity\Strategy $strategy)
    {
        foreach ($this->getBuilders() as $builder) {
            if (true === $builder->supports($strategy)) {
                return $builder->build($strategy);
            }
        }

        return 0;
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\Strategy $strategy
     *
     * @return bool
     */
    protected function supports(Entity\Strategy $strategy)
    {
        return true;
    }

    /**
     * @return Builder[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    public function addBuilder(Builder $builder)
    {
        $this->builders[] = $builder;

        return $this;
    }
}
