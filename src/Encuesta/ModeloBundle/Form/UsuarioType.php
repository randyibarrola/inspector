<?php
namespace Encuesta\ModeloBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Encuesta\ModeloBundle\Entity\Rol;

class UsuarioType extends AbstractType
{
    var $datosPerfil;

    public function __construct ($datos_perfil = false)
    {
        $this->datosPerfil  = $datos_perfil;
    }    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {     
        $rol_id = "";
        $obj = $builder->getData();
        foreach($obj->getUsuarioRoles() as $rol)
            $rol_id = $rol->getId();
        $builder            
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Nombre',
                    'size'=>40
                ),
                'required' => false               
            ))   
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos',
                'attr' => array(
                    'class' => 'validate[required]',
                    'placeholder' => 'Apellidos',
                    'size'=>40
                ),
                'required' => false

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
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'validate[required, custom[email]] email',
                    'placeholder' => 'Email',
                    'size'=>40
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
            ))   
                
            ->add('imagen', 'file', array(
                'label' => 'Imagen',
                'mapped' => false,
                'attr' => array(                    
                    'placeholder' => 'Imagen',
                    'size'=>20
                ),
                'required' => false
               
            ))                
                
            ->add('rol', 'choice', array(
                'label' => 'Rol',
                'mapped' => false,
                'attr' => array(
                    'class' => '',
                    'placeholder' => 'Email',
                    
                ),
                'required' => true,
                'constraints' => array(
                    new NotBlank()                   
                   ),
                'choices'   => array(
                    '1'   => 'Administrador',
                    '2' => 'Invitado'                
                ),
                'data' => $rol_id,
                'expanded' => false,
                'multiple' => false,
            )) ;  
        
        //Para el caso de que solo se desee editar el perfil del usuario
        
        if(! $this->datosPerfil) {
            $builder 
                ->add('username', 'text', array(
                    'label' => 'Nombre de usuario',
                    'attr' => array(
                        'class' => 'validate[required] required',
                        'placeholder' => 'Nombre de usuario',
                        'size'=>40
                    ),
                    'required' => true,
                    'constraints' => array(
                        new NotBlank()                   
                       ),
                    )                
                )   
                ->add('password', 'password', array(
                    'label' => 'Contraseña',
                    'attr' => array(
                        'class' => '',
                        'placeholder' => 'Contraseña',
                        'size'=>40
                    ),
                    'required' => false
                   
                )) 
                ;                     
        }
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Encuesta\ModeloBundle\Entity\Usuario'            
        ));       
    }

    public function getName()
    {
        return 'encuesta_modelobundle_usuariotype';
    }
}