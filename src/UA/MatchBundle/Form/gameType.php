<?php

namespace UA\MatchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class gameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('shortName')
            ->add('file', 'file')
            ->add('generator', 'choice', array(
                                        'choices' => array('X' => 'No generator found !!')
                                        ))
            ->add('nbrPlayerRequired');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UA\MatchBundle\Entity\game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ua_matchbundle_gametype';
    }
}
