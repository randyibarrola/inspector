<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

class MedicoType extends AbstractType
{
  
  

    public function __construct ()
    {
        
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        
        $builder            
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Nombre',
                    'size'=>40
                ),
                'required' => true               
            ))   
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Apellidos',
                    'size'=>40
                ),
                'required' => true

            ))       
            ->add('especialidad', 'text', array(
                'label' => 'Especialidad',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Especialidad',
                    'size'=>40
                ),
                'required' => true

            ))                
            ->add('consultorio', 'text', array(
                'label' => 'Consultorio',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Consultorio',
                    'size'=>40
                ),
                'required' => true
                
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Email',
                    'size'=>40
                ),
                'required' => false
                
            ))                
            ->add('pasillo', 'text', array(
                'label' => 'Pasillo',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Pasillo',
                    'size'=>40
                ),
                'required' => true
                
            ));  
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Medico'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_medicotype';
    }

}