<?php

namespace UA\MatchBundle\Form\Match;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class teamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('game', 'entity',  array(
                                'class' => 'UAMatchBundle:game',
                                'property' => 'name'
                                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UA\MatchBundle\Entity\Match\team'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ua_matchbundle_match_teamtype';
    }
}
