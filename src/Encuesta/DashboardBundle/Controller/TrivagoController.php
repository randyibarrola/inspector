<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Encuesta\DashboardBundle\Util\GestionBooking;
use Encuesta\ModeloBundle\Entity\Consulta;
use Encuesta\ModeloBundle\Form\ConsultaType;

class TrivagoController extends Controller
{
    public function busquedaAction()
    {
        $resultados = array();
        if($this->getRequest()->getMethod() == 'POST') {
            $url = $this->getRequest()->get('url');
            
            $resultados = GestionBooking::getResultadosTrivago($url);
        }
        
        return $this->render('DashboardBundle:Trivago:busqueda.html.twig', array(
            'intervalos' => $resultados
        ));
    }  
    
    public function listadoAction(){
        $em = $this->getDoctrine()->getManager();
        $consultas = $em->getRepository('ModeloBundle:Consulta')->findAll();
        return $this->render('DashboardBundle:Trivago:listado.html.twig', array(
            'list' => $consultas
        ));        
    }
    
    public function newAction()
    {
        $request = $this->getRequest();

        $obj = new Consulta();
        $form = $this->createForm(new ConsultaType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form);
        }

        return $this->render('DashboardBundle:Trivago:form.html.twig', array(            
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
                
                    
                    $em->persist($data);
                    $em->flush();
              

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
    
}

/*
 * 
 * 
 */

?>
