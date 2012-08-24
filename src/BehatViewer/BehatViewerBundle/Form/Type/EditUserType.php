<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\CallbackValidator,
    Symfony\Component\Form\Form;

/**
 *
 */
class EditUserType extends CreateUserType
{
    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
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
			))
		;

        $builder->addValidator(new CallbackValidator(function(Form $form) {
            $password = $form->get('password');
            $confirm = $form->get('confirm');

            if ($confirm->getData() !== $password->getData()) {
                $password->addError(new \Symfony\Component\Form\FormError('Passwords are not identical'));
                $confirm->addError(new \Symfony\Component\Form\FormError(''));
            }
        }));
    }
}
