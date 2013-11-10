<?php
namespace UA\UserBundle\Twig;

class UAExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'ceil' => new \Twig_Filter_Method($this, 'ceil'),
        );
    }

    public function getFunctions()
    {
        return array(
            'file_exists' => new \Twig_Function_Function('file_exists'),
            );
    }

    public function ceil($number)
    {
        return ceil($number);
    }

    public function getName()
    {
        return 'acme_extension';
    }
}