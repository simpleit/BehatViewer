<?php
namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\CallbackValidator,
    Symfony\Component\Form\Form;

/**
 *
 */
class CreateUserType extends UserType
{
    private $passwordRequired;
    private $allowActive;

    public function __construct($passwordRequired = true, $allowActive = true)
    {
        $this->passwordRequired = $passwordRequired;
        $this->allowActive = $allowActive;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		parent::buildForm($builder, $options);

        $builder
            ->add(
                'password',
                'password',
                array(
                    'label' => 'Password',
                    'required' => $this->passwordRequired,
                    'property_path' => false,
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
                    'required' => $this->passwordRequired,
                    'property_path' => false,
                    'attr' => array(
                        'class' => 'input-xlarge'
                    )
                )
            )
        	->add(
                'isActive',
                'choice',
                array(
                    'label' => 'Active',
                    'required' => true,
                    'choices' => array(
                        '1' => 'Yes',
                        '0' => 'No',
                    )
                )
            )
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
