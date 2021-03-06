<?php

namespace Encuesta\ModeloBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nombre', 'text', array(
                'label' => false,
                'required' => true
            ))
            ->add('descripcion', 'textarea', array(
                'label' => false,
                'required' => false
            ))
           
            ->add('cantidad_imagenes', 'integer', array(
                'label' => false,
                'required' => false                
            ));
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Categoria'
        ));
    }

    public function getName() {
        return 'encuesta_modelobundle_categoriatype';
    }

}

