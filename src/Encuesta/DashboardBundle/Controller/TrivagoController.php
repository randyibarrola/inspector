<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Encuesta\DashboardBundle\Util\GestionBooking;
use Encuesta\ModeloBundle\Entity\Consulta;
use Encuesta\ModeloBundle\Form\ConsultaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Encuesta\ModeloBundle\Entity\Inspeccion;
use Encuesta\ModeloBundle\Entity\InspeccionResultado;

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
            'resultados' => $resultados
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
            return $this->save($form, $request);
        }

        return $this->render('DashboardBundle:Trivago:form.html.twig', array(            
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
                
                $ids = $request->get('ids');
                $inicio = $request->get('inicio');
                $fin = $request->get('fin');
                $ejecucion = $request->get('ejecucion');
                
               /* var_dump($ids);
                var_dump($inicio);
                var_dump($fin);
                
                var_dump($ejecucion);die;*/
                
                $inspecciones = $data->getInspecciones();
                foreach($inspecciones as $inspeccion){
                    if(!in_array($inspeccion->getId(), $ids)){
                       $em->remove($inspeccion)  ;
                    }
                } 
                
                foreach($ids as $key => $id){
                    $inspeccion = $id > 0 ? $em->getRepository('ModeloBundle:Inspeccion')->find($id) : new Inspeccion();
                    $inspeccion->setFechaEjecucion(\DateTime::createFromFormat('d/m/y',$ejecucion[$key]));
                    $inspeccion->setFechaInicio(\DateTime::createFromFormat('d/m/y', $inicio[$key]));
                    $inspeccion->setFechaFin( \DateTime::createFromFormat('d/m/y', $fin[$key]) );
                    $inspeccion->setConsulta($data);
                    $em->persist($inspeccion);                    
                }
                
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
    
    public function editAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $form = $this->createForm(new ConsultaType(), $obj);

        if($request->getMethod() == 'POST') {
            
            $response = $this->save($form, $request);

            return $response;
        }

        
        return $this->render('DashboardBundle:Trivago:form.html.twig', array(
            'obj' => $obj,
            'form' => $form->createView()
        ));
    }  
    
    public function showAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
               
        return $this->render('DashboardBundle:Trivago:detalle.html.twig', array(
            'consulta' => $obj
        ));
    }    
    
    public function ejecutarAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $inspecciones = $obj->getInspecciones();
        $resultados = array();
        foreach($inspecciones as $inspeccion){
            if(!$inspeccion->getEjecutada()){
                $resultados = GestionBooking::getResultadosTrivago($obj->getUrl(), $inspeccion->getFechaInicio()->format('Y-m-d'), $inspeccion->getFechaFin()->format('Y-m-d'));
                
                if(isset($resultados['canales']) && count($resultados['canales']) > 0) {   
                    for($i = 0; $i < count($resultados['canales']); $i++ ){
                        $inspResultado = new InspeccionResultado();
                        $inspResultado->setInspeccion($inspeccion);
                        $inspResultado->setCanal($resultados['canales'][$i]);
                        $inspResultado->setPrecio($resultados['precios'][$i]);
                        if($i === 0)
                            $inspResultado->setMejor(1);
                        $inspResultado->setSrc($resultados['src'][$i]);
                        $em->persist($inspResultado);
                    }
                    $inspeccion->setFechaEjecucion(new \DateTime(date('Y-m-d')));
                    $inspeccion->setEjecutada(1);
                }

                $em->persist($inspeccion);
            }
        }
        
        $em->flush();
        return $this->render('DashboardBundle:Trivago:detalle.html.twig', array(
            'consulta' => $obj            
        ));
    }      
    
    
}

/*
 * 
 * 
 */

?>
