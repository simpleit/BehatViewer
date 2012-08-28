<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type;

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
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array                               $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'repository_url',
                'text',
                array(
                    'label' => 'Repository URL',
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
