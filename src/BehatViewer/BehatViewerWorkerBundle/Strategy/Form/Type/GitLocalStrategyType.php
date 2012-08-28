<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\CallbackValidator,
    Symfony\Component\Form\Form,
    Symfony\Component\Form\AbstractType;

/**
 *
 */
class GitLocalStrategyType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'git_local';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'repository_path',
                'text',
                array(
                    'label' => 'Repository path',
                    'required' => true,
                    'attr' => array(
                        'class' => 'input-xxlarge'
                    )
                )
            )
            ->add(
                'branch',
                'text',
                array(
                    'label' => 'Branch',
                    'required' => true,
                    'attr' => array(
                        'class' => 'input-xxlarge'
                    )
                )
            )
        ;
    }
}
