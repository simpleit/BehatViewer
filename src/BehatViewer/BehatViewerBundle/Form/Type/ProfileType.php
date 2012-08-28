<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProfileType extends UserType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('token', 'text', array(
                'label' => 'API token',
                'attr' => array(
                    'class' => 'input-xlarge',
                    'readonly' => 'readonly'
                )
            ));
        ;
    }
}
