<?php

namespace BehatViewer\BehatViewerBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ProjectRepositoryType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add(
				'strategy',
				'choice',
				array(
					'label' => 'Type',
					'choices' => array(
						'git' => 'Git repository',
						'local' => 'Local directory',
						'git_local' => 'Local Git repository',
						'github' => 'Github repository'
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
        return 'project_script';
    }
}
