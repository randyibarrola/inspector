<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

class PacienteType extends AbstractType
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
            ->add('categoria', 'entity', array(
                'empty_value' => 'Seleccione tipo de afiliación',
                'label' => false,
                'required' => true,
                'class' => 'ModeloBundle:Categoria',
                'property' => 'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                }
            ))                
            ->add('telefono', 'text', array(
                'label' => 'Teléfono',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Teléfono',
                    'size'=>40
                ),
                'required' => false
                
            ))
            ->add('celular', 'text', array(
                'label' => 'Teléfono',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Celular',
                    'size'=>40
                ),
                'required' => true
                
            ))                
            ->add('cedula', 'text', array(
                'label' => 'Cédula',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Cédula',
                    'size'=>40,
                    'maxlength' =>10
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

            ->add('imagen_cedula', 'file', array(
                'label' => 'Imagen',
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Imagen Cédula',
                    'size'=>20
                ),
                'required' => false,
                'mapped' => false
                
            ));  
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Paciente'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_pacientetype';
    }
}