<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProjectScriptType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'      => 'BehatViewer\BehatViewerBundle\Entity\Project'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'project_script';
    }
}
