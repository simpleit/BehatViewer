<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
abstract class UserType extends AbstractType
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'      => 'BehatViewer\BehatViewerBundle\Entity\User',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'user_item',
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                array(
                    'label' => 'Username',
                    'attr' => array(
                        'class' => 'input-xlarge'
                    )
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'E-mail',
                    'attr' => array(
                        'class' => 'input-xlarge'
                    )
                )
            )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'User';
    }
}
