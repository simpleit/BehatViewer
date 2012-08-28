<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProjectType extends AbstractType
{
    protected $strategies;

    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Project name',
                    'attr' => array(
                        'class' => 'input-xlarge'
                    )
                )
            )
            ->add(
                'slug',
                'text',
                array(
                    'label' => 'Identifier',
                    'attr' => array(
                        'class' => 'input-xlarge'
                    )
                )
            )
            ->add(
                'test_command',
                'textarea',
                array(
                    'label' => 'Test command',
                    'attr' => array(
                        'rows' => 10,
                        'cols' => 70,
                        'style' => 'width: auto'
                    )
                )
            )
            ->add(
                'strategy',
                'choice',
                array(
                    'label' => 'Type',
                    'required' => true,
                    'choices' => $this->getStrategyChoices($this->strategies)
                )
            )
        ;
    }

    protected function getStrategyChoices($strategies)
    {
        foreach ($strategies as &$strategy) {
            $strategy = $strategy::getLabel();
        }

        return $strategies;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'      => 'BehatViewer\BehatViewerBundle\Entity\Project',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'project_item',
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Project';
    }
}
