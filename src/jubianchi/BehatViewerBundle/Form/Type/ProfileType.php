<?php

namespace jubianchi\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProfileType extends UserType
{
    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'attr' => array(
                    'class' => 'input-xlarge'
                )
            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'class' => 'input-xlarge'
                )
            ));
    }
}
