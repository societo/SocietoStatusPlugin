<?php

namespace SocietoPlugin\Societo\StatusPlugin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StatusType extends AbstractType
{
    private $maxLength = null;

    public function __construct($maxLength = null)
    {
        $this->maxLength = $maxLength;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('body', 'textarea', array('max_length' => $this->maxLength))
            ->add('redirect_to', 'hidden', array('property_path' => false));
    }

    public function getName()
    {
        return 'societo_status_plugin_status';
    }
}
