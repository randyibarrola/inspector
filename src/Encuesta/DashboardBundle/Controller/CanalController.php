<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Encuesta\ModeloBundle\Entity\Canal;
use Encuesta\ModeloBundle\Form\CanalType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;


class CanalController extends Controller
{    
    
    public function listadoAction(){
        $em = $this->getDoctrine()->getManager();
        $canales = $em->getRepository('ModeloBundle:Canal')->findAll();
        return $this->render('DashboardBundle:Canal:listado.html.twig', array(
            'list' => $canales
        ));        
    }
    
    public function newAction()
    {
        $request = $this->getRequest();

        $obj = new Canal();
        $form = $this->createForm(new CanalType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form, $request);
        }

        return $this->render('DashboardBundle:Canal:form.html.twig', array(            
            'obj' => $obj,
            'form' => $form->createView()            
        ));
    }    
    
    private function save($form, $request)
    {      
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $response = $this->get('dashboard.ajaxresponse');
        try {
            $form->bind($request);
            
           
            if($form->isValid()) {

                $data = $form->getData(); 
                $em->persist($data);
                
                $em->flush();
              
                $response->setUrl( $this->generateUrl('dashboard_canal_listado') );
                $response->setMessage($translator->trans('La consulta se ha guardado satisfactoriamente'));
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

        $obj = $em->getRepository('ModeloBundle:Canal')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el canal que está intentando editar');
        
        $form = $this->createForm(new CanalType(), $obj);

        if($request->getMethod() == 'POST') {
            
            $response = $this->save($form, $request);
            
            
            return $response;
        }

        
        return $this->render('DashboardBundle:Canal:form.html.twig', array(
            'obj' => $obj,
            'form' => $form->createView()
        ));
    }  
    
    public function showAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Canal')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el canal que está intentando editar');
        
               
        return $this->render('DashboardBundle:Canal:detalle.html.twig', array(
            'consulta' => $obj
        ));
    }    
    
   
    public function deleteAction()
    {        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Canal')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el canal que está intentando editar');
        
        $em->remove($obj);  
        $em->flush();
        
        return $this->redirect($this->generateUrl( 'dashboard_canal_listado'));
    } 
    
}

/*
 * 
 * 
 */

?>
