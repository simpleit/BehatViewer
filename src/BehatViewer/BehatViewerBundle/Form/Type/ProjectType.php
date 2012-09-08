<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProjectType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
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
