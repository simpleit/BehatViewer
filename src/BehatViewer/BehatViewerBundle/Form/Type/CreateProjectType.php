<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class CreateProjectType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['flowStep']) {
            case 1:
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
                        'strategy',
                        'choice',
                        array(
                            'label' => 'Type',
                            'required' => true,
                            'property_path' => false,
                            'choices' => array(
                                'local' => 'Local directory',
                                'git' => 'Git repository',
                                'git_local' => 'Local Git repository',
                                'github' => 'Github repository'
                            ),
                            'attr' => array(
                                'class' => 'input-xlarge'
                            )
                        )
                    )
                ;
                break;

            case 2:
                $builder
                    ->add(
                        'branch',
                        'text',
                        array(
                            'label' => 'Branch'
                        )
                    )
                ;
                break;

            case 3:
                $builder
                    ->add(
                        'test_command',
                        'textarea',
                        array(
                            'label' => 'Test script',
                            'label_attr' => array(
                                'style' => 'display: block'
                            ),
                            'attr' => array(
                                'rows' => 10,
                                'cols' => 70,
                                'style' => 'width: auto'
                            )
                        )
                    )
                ;
                break;
        }
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'flowStep'        => 1,
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
        return 'createProject';
    }
}
