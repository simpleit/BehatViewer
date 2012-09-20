<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class RegisterType extends UserType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
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
			->add(
				'password',
				'password',
				array(
					'label' => 'Password',
					'attr' => array(
						'class' => 'input-xlarge'
					)
				)
			)
			->add(
				'confirm',
				'password',
				array(
					'label' => 'Confirm password',
					'property_path' => false,
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
        return 'Register';
    }
}
