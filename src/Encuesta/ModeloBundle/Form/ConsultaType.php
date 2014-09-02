<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

class ConsultaType extends AbstractType
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
            ->add('hotel', 'text', array(
                'label' => 'hotel',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Nombre del hotel',
                    'size'=>40
                ),
                'required' => true

            ))       
            ->add('url', 'url', array(
                'label' => 'Url de trivago.es',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Ej. http://www.trivago.es/corralejo-31790/hotel/oasis-papagayo-sport---family-15992',
                    'size'=>80
                ),
                'required' => true

            ))  ;              
           
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Consulta'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_consultatype';
    }

}