<?php
namespace BehatViewer\BehatViewerAdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\CallbackValidator,
    Symfony\Component\Form\Form,
    BehatViewer\BehatViewerBundle\Form\Type\UserType;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CreateUserType extends UserType
{
    private $passwordRequired;
    private $allowActive;

    /**
     * @param bool $passwordRequired
     * @param bool $allowActive
     */
    public function __construct($passwordRequired = true, $allowActive = true)
    {
        $this->passwordRequired = $passwordRequired;
        $this->allowActive = $allowActive;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
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

        $builder->add(
            'roles',
            'entity',
            array(
                'label' => 'Roles',
                'multiple' => true,
                'property_path' => 'rolesCollection',
                'class'=>'BehatViewer\BehatViewerBundle\Entity\Role',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                }
            )
        );

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
