<?php

namespace Acme\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', 'text', array('required' => false));

        $width = $builder->create('width', 'form', array('compound' => true));

        $width->add('from', 'number', array('required' => false));

        $width->add('to', 'number', array('required' => false));

        $builder->add($width);

        $height = $builder->create('height', 'form', array('compound' => true));

        $height->add('from', 'number', array('required' => false));

        $height->add('to', 'number', array('required' => false));

        $builder->add($height);

        $weight = $builder->create('weight', 'form', array('compound' => true));

        $weight->add('from', 'number', array('required' => false));

        $weight->add('to', 'number', array('required' => false));

        $builder->add($weight);

        $builder->add('search', 'submit');

        $builder->add('elastic_search', 'submit');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_search';
    }
}