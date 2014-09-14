<?php
namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Encuesta\ModeloBundle\Entity\Usuario;
use Encuesta\ModeloBundle\Form\UsuarioType;
use Symfony\Component\Form\FormInterface;


class UserController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $session->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render('DashboardBundle:User:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Usuario')->findAll();
        return $this->render('DashboardBundle:User:list.html.twig', array(
            'list' => $list,
            'nombre_rol' => $this->container->getParameter('nombre_rol')
        ));
    }

    public function changeStateAction()
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $peticion = $this->getRequest();

        $response = $this->get('dashboard.ajaxresponse');
        try {
            $obj = $em->getRepository('ModeloBundle:Usuario')->find($peticion->get('id'));

            if(!$obj) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('Está intentando modificar un usuario que no existe'));
            }
            else {
                $obj->setActivo(!$obj->getActivo());
                $em->persist($obj);
                $em->flush();

                $response->setMessage($translator->trans('Se ha modificado el estado del usuario correctamente'));
            }
        }
        catch(\Exception $e) {
            $response->setHttpCode(500);
            $response->setMessage($translator->trans('Lo sentimos, hubo un error'));
        }

        $sResponse = new Response(json_encode($response->response()));
        $sResponse->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $sResponse;
    }

    public function viewAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('ModeloBundle:Usuario')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el usuario que está intentando ver');

        return $this->render('DashboardBundle:User:view.html.twig', array(
            'obj' => $obj,
            'nombre_rol' => $this->container->getParameter('nombre_rol', array())
        ));
    }

    public function deleteAction()
    {
        $response = $this->get('dashboard.ajaxresponse');
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        try {
            $id = $this->getRequest()->get('id');
            if($id == $this->getUser()->getId()) {
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('No se puede eliminar el usuario que está logueado en la aplicación'));
            }
            else {
                $obj = $em->getRepository('ModeloBundle:Usuario')->find($id);

                if(!$obj) {
                    $response->setHttpCode(500);
                    $response->setMessage($translator->trans('El que intenta eliminar no existe'));
                }
                else {
                    $em->remove($obj);
                    $em->flush();

                    $response->setMessage($translator->trans('El se ha eliminado satisfactoriamente'));
                    $response->setDataHolder(array('route' => $this->get('router')->generate('dashboard_usuario')));
                }
            }
        }
        catch(\Exception $e) {
            $response->setHttpCode(500);
            $response->setMessage($translator->trans('Lo sentimos, ha ocurrido un error'));
        }

        return new Response(json_encode($response->response()));
    }
    
    public function newAction()
    {
        $request = $this->getRequest();

        $obj = new Usuario();
        $form = $this->createForm(new UsuarioType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form);
        }

        return $this->render('DashboardBundle:User:form.html.twig', array(            
            'obj' => $obj,
            'form' => $form->createView()            
        ));
    }    
    
    private function save(FormInterface $form, $passwordOriginal = null)
    {      
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $response = $this->get('dashboard.ajaxresponse');
        try {
            $form->bind($request);
            $data = $form->getData();
            $email = $data->getEmail();
            $user = $em->getRepository('ModeloBundle:Usuario')->findOneBy(array('email'=>$email));
           
            if($form->isValid() && !$user) {

                $data = $form->getData();
                $origen = $data->getImagen();
                
                
                
                $dir = $this->container->getParameter('upload_dir').'perfil/'.$data->getId();
                if(!is_dir($dir)){
                    mkdir($dir); 
                }    
                
                if($form['imagen']->getData()) {
                     $nombre = sha1(uniqid(mt_rand(), true) ).'.'.$form['imagen']->getData()->getClientOriginalExtension() ;
                     $form['imagen']->getData()->move($dir,$nombre);
                     
                     $data->setImagen($nombre);
                } else {
                    $data->setImagen($origen);
                }                
                                 
                    
                    
                    if( strlen($form->get('password')->getData()) > 0){
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($data);
                        $salt = $data->getSalt() ? $data->getSalt() : md5(time());
                        $data->setSalt($salt); 
                        $password = $encoder->encodePassword($form->get('password')->getData(),$salt );
                        $data->setPassword($password);
                    } else {
                        $data->setPassword($passwordOriginal);
                    }                   
                    
                    
                    $ur = $data->getUsuarioRoles();
                    foreach($ur as $_ur) {
                        $data->removeUsuarioRole($_ur);                      
                    }
                    
                    $rol = $em->getRepository('ModeloBundle:Rol')->find($form->get('rol')->getData());   
                    $data->addUsuarioRole($rol);
                    
                    $em->persist($data);
                    $em->flush();
              

                $response->setMessage($translator->trans('El usuario se ha guardado satisfactoriamente'));
            }
            else {
                $response = $this->get('dashboard.ajaxformresponse');
                $response->setForm($form);
                $response->setHttpCode(500);
                $response->setMessage($translator->trans('Existen datos incorrectos en el formulario'));
            }
        }
        catch(\Exception $e) {
            $response->setHttpCode(500);
            $response->setMessage($translator->trans($e->getMessage()));
        }

        $sResponse = new Response(json_encode($response->response()));
        $sResponse->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $sResponse;
    }     
    
    public function editAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Usuario')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el usuario que está intentando editar');
        
        $passwordOriginal = $obj->getPassword();

        $form = $this->createForm(new UsuarioType(), $obj);

        if($request->getMethod() == 'POST') {
            //$imagen_original = $form->getData()->getImagenCedula();
            //$eliminar_imagen = $request->request->get('imagen_delete', 0);
            $response = $this->save($form, $passwordOriginal);

            /*$obj = $form->getData();
            if($obj->getImagenCedula() === null && $eliminar_imagen == 0) {
                $obj->setImagenCedula($imagen_original);

                $em->persist($obj);
                $em->flush();
            }
            else if($imagen_original != null) {
                unlink($this->container->getParameter('upload_dir').'paciente/'.$obj->getId().'/'.$imagen_original);
                if(is_dir($this->container->getParameter('upload_dir').'paciente/'.$obj->getId()))
                    @rmdir($this->container->getParameter('upload_dir').'paciente/'.$obj->getId());
            }*/

            return $response;
        }

        
        return $this->render('DashboardBundle:User:form.html.twig', array(
            'obj' => $obj,
            'form' => $form->createView()
        ));
    }    
}