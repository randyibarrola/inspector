<?php
namespace Encuesta\DashboardBundle\Controller;

use Encuesta\ModeloBundle\Entity\Paciente;
use Encuesta\ModeloBundle\Entity\Imagen;
use Encuesta\ModeloBundle\Form\PacienteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class PacienteController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Paciente')->findAll();

        return $this->render('DashboardBundle:Paciente:list.html.twig', array(
            'list' => $list            
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

        
        $obj = $em->getRepository('ModeloBundle:Paciente')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el paciente que está intentando ver');
        
        $turnos = $obj->getTurnos();
        $turno = null;
        foreach($turnos as $t) {
            if($t->getFecha() == date('Ymd'))
                $turno = $t;
        }
        
        $imagenes = $obj->getImagenes();

        return $this->render('DashboardBundle:Paciente:view.html.twig', array(
            'obj' => $obj,
            'nombre_rol' => $this->container->getParameter('nombre_rol', array()),
            'turno' => $turno,
            'imagenes' => $imagenes
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
                $response->setMessage($translator->trans('No se puede eliminar el paciente que está logueado en la aplicación'));
            }
            else {
                $obj = $em->getRepository('ModeloBundle:Paciente')->find($id);

                if(!$obj) {
                    $response->setHttpCode(500);
                    $response->setMessage($translator->trans('El paciente que intenta eliminar no existe'));
                }
                else {
                    $em->remove($obj);
                    $em->flush();

                    $response->setMessage($translator->trans('El se ha paciente eliminado satisfactoriamente'));
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

        $obj = new Paciente();
        $form = $this->createForm(new PacienteType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form);
        }

        return $this->render('DashboardBundle:Paciente:form.html.twig', array(            
            'obj' => $obj,
            'form' => $form->createView()            
        ));
    }
    
    private function save(FormInterface $form)
    {      
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $response = $this->get('dashboard.ajaxresponse');
        try {
            $form->bind($request);
            if($form->isValid()) {
                $data = $form->getData();
                $origen = $data->getImagenCedula();
                if($data->getId() == null) {
                    $em->persist($data);
                    $em->flush();
                }
                
                $dir = $this->container->getParameter('upload_dir').'paciente/'.$data->getId();
                if(!is_dir($dir)){
                    mkdir($dir); 
                }
                
                $cantidad = $data->getCategoria()->getCantidadImagenes();  
                $extras = $data->getImagenes();
                
                if(count($extras) > $cantidad){
                    foreach($extras as $extra){
                        $em->remove($extra);
                    }
                }
                
                for($i = 1; $i <= $cantidad; $i++){
                    if( isset($_FILES['extra_'.$i]) &&  $_FILES['extra_'.$i]["tmp_name"] != "" ) {
                        $tmp_name = $_FILES['extra_'.$i]["tmp_name"];
                        $name = $_FILES['extra_'.$i]["name"];
                        move_uploaded_file($tmp_name, "$dir/$name");
                        
                        if(isset($extras[$i])){
                            $extras[$i]->setRuta("/uploads/paciente/".$data->getId().'/'.$name);
                            $em->persist($extras[$i]);
                        } else {
                            $extra = new Imagen();
                            $extra->setPaciente($data);
                            $extra->setRuta("/uploads/paciente/".$data->getId().'/'.$name);
                            $em->persist($extra);
                        }
                    }
                }
                
                if($form['imagen_cedula']->getData()) {
                     $nombre = sha1(uniqid(mt_rand(), true) ).'.'.$form['imagen_cedula']->getData()->getClientOriginalExtension() ;
                     $form['imagen_cedula']->getData()->move($dir,$nombre);
                     
                     $data->setImagenCedula($nombre);
                } else {
                    $data->setImagenCedula($origen);
                }
                
                        
                //$imagen = $this->get('dashboard.file')->uploadFile($data->getImagenCedula(), $dir);
                

                $em->persist($data);
                $em->flush();      
                
                $response->setUrl($this->generateUrl ('dashboard_paciente_ver', array('id'=>$data->getId())));
                $response->setMessage($translator->trans('El paciente se ha guardado satisfactoriamente'));
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
    
    public function renderImagenesExtrasAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $cantidad = $request->get('cantidad');
        $paciente_id = $request->get('paciente_id');
        $categoria = $request->get('categoria', null);
        if($categoria) { 
           $obj = $em->getRepository('ModeloBundle:Categoria')->find($categoria); 
           $cantidad = $obj->getCantidadImagenes();
        }
        $paciente = $em->getRepository('ModeloBundle:Paciente')->find($paciente_id); 

        return $this->render('DashboardBundle:Paciente:imagenes.html.twig', array(            
            'cantidad' => $cantidad ,
            'imagenes' => $paciente ? $paciente->getImagenes() : array()
        ));        
    }
    
    public function editAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Paciente')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el paciente que está intentando editar');

        $form = $this->createForm(new PacienteType(), $obj);

        if($request->getMethod() == 'POST') {
            //$imagen_original = $form->getData()->getImagenCedula();
            //$eliminar_imagen = $request->request->get('imagen_delete', 0);
            $response = $this->save($form);

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

        
        return $this->render('DashboardBundle:Paciente:form.html.twig', array(
            'obj' => $obj,
            'form' => $form->createView()
        ));
    }
    
    public function buscadorAction()
    {
        $resultados = array();
        if($this->getRequest()->getMethod() == "POST") {
            $texto = $this->getRequest()->get('buscador');
            $em = $this->getDoctrine()->getManager();
            $resultados = $em->getRepository('ModeloBundle:Paciente')->findPacienteByTexto($texto);            
        }
        return $this->render('DashboardBundle:Paciente:buscador.html.twig', array(            
            'resultados'=> $resultados       
        ));        
    }
    
    public function detalleAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Paciente')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el paciente que está intentando editar');  
        
        return $this->render('DashboardBundle:Paciente:detalle.html.twig', array(
            'obj' => $obj            
        ));        
    }
}