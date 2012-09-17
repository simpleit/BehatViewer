<?php
namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\CallbackValidator,
    Symfony\Component\Form\Form,
    Symfony\Component\Form\AbstractType;

/**
 *
 */
class GitStrategyType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'git';
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class'      => 'BehatViewer\BehatViewerCoreBundle\Entity\GitStrategy'
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
                'url',
                'text',
                array(
                    'label' => 'Repository URL',
                    'required' => true,
                    'attr' => array(
                        'class' => 'input-xlarge'
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
                        'class' => 'input-xlarge'
                    )
                )
            )
        ;
    }
}
