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
        $consultas = $em->getRepository('ModeloBundle:Consulta')->findBy(array('usuario'=>$this->getUser()->getId()));
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
                $data->setUsuario($this->getUser());
                $numero = $data->getNumeroEjecucion();      
                if(!$data->getId()){
                   $data->setNumeroEjecucion(0); 
                } else {
                   $data->setNumeroEjecucion($numero++);  
                }
                    
                $em->persist($data);
                
                $ids = $request->get('ids');
                $inicio = $request->get('inicio');
                $fin = $request->get('fin');
                $ejecucion = $request->get('ejecucion');

                
                $inspecciones = $data->getInspecciones();
                foreach($inspecciones as $inspeccion){
                    if(!in_array($inspeccion->getId(), $ids)){
                       $em->remove($inspeccion)  ;
                    }
                } 
                
                foreach($ids as $key => $id){
                    if( $ejecucion[$key] && $inicio[$key] && $fin[$key]) {
                        $inspeccion = $id > 0 ? $em->getRepository('ModeloBundle:Inspeccion')->find($id) : new Inspeccion();
                        $inspeccion->setFechaEjecucion(\DateTime::createFromFormat('d/m/y',$ejecucion[$key]));
                        $inspeccion->setFechaInicio(\DateTime::createFromFormat('d/m/y', $inicio[$key]));
                        $inspeccion->setFechaFin( \DateTime::createFromFormat('d/m/y', $fin[$key]) );
                        $inspeccion->setConsulta($data);
                        $em->persist($inspeccion);  
                    }
                }
                
                $em->flush();
              
                $response->setUrl( $this->generateUrl('dashboard_trivago_consultas') );
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
        $estados = array();
        $resultados = array();
        $ahora = new \DateTime('now');
        
        
        
        foreach($inspecciones as $inspeccion){
            foreach($inspeccion->getResultados() as $r)
                $em->remove ($r);
        }

        $em->flush();     
        
        return $this->redirect($this->generateUrl('dashboard_trivago_consulta_ejecutar_instacia', array('id'=>$request->get('id'))));

        /*
        return $this->render('DashboardBundle:Trivago:detalle.html.twig', array(
            'consulta' => $obj            
        ));*/
    } 
    
    
    public function ejecutar1Action()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $inspecciones = $obj->getInspecciones();
        $estados = array();
        $resultados = array();
        $ahora = new \DateTime('now');
        $promedios = array('menor'=>array(),'igual'=>array(), 'mayor'=>array() );
        
        foreach($inspecciones as $inspeccion){
            if($inspeccion->getFechaInicio() > $ahora){
                
                $resultados = GestionBooking::getResultadosTrivago($obj->getUrl(), $inspeccion->getFechaInicio()->format('Y-m-d'), $inspeccion->getFechaFin()->format('Y-m-d'));
                
                $valorMenor = 0;
                $estadoBooking = -2;
                
                if(isset($resultados['canales']) && count($resultados['canales']) >= 0) {  
                    
                    for($i = 0; $i < count($resultados['canales']); $i++ ){                        
                        $inspResultado = new InspeccionResultado();
                        $inspResultado->setInspeccion($inspeccion);
                        $inspResultado->setCanal($resultados['canales'][$i]);
                        $inspResultado->setPrecio($resultados['precios'][$i]);
                        if($i === 0){
                            $inspResultado->setMejor(1);
                            $valorMenor= $resultados['precios'][$i];                            
                            $promedios['igual'][] = $valorMenor;
                            if($resultados['canales'][$i] == 'Booking.com'){
                               $estadoBooking = 1; 
                               $promedios['menor'][] = $valorMenor;
                            }
                        } else {
                            if($resultados['canales'][$i] == 'Booking.com'){
                               if($resultados['precios'][$i] == $valorMenor){
                                   $estadoBooking = 0;                                   
                               } else {
                                   $estadoBooking = -1;   
                                   $promedios['mayor'][]=$resultados['precios'][$i];
                               }
                               $estadoBooking = $resultados['precios'][$i] == $valorMenor ? 0 : -1;  
                            }
                        }
                        //$inspResultado->setSrc($resultados['src'][$i]);
                        $inspeccion->setUrl($resultados['url']);
                        $em->persist($inspResultado);                        
                        
                    }
                    $inspeccion->setFechaEjecucion(new \DateTime(date('Y-m-d')));
                    $inspeccion->setEjecutada(1);
                    $inspeccion->setEstadoBooking($estadoBooking);
                    $estados[]= $estadoBooking;
                    
                }

                $em->persist($inspeccion);
                
            }
            else {
               $estados[]= $inspeccion->getEstadoBooking(); 
            }
        }
        
        $numero = $obj->getNumeroEjecucion();
        $obj->setNumeroEjecucion(++$numero);
        
        $obj->setPorcentajesDesdeEstados($estados);
        $obj->setPromediosDesdePrecios($promedios);
        $em->persist($obj);
        $em->flush();     
        
        

        
        return $this->render('DashboardBundle:Trivago:detalle.html.twig', array(
            'consulta' => $obj            
        ));
    }        
    
    public function deleteAction()
    {        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $em->remove($obj);  
        $em->flush();
        
        return $this->redirect($this->generateUrl( 'dashboard_trivago_consultas'));
    }    
    
    public function actualizarAprobacionAction()
    {        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('pk'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $obj->setAprobacion($request->get('value'));
        
        $em->persist($obj);
        $em->flush();
        
        return new Response();
        
        
    }
    
    public function actualizarTarifaAction()
    {        
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        

        $obj = $em->getRepository('ModeloBundle:Consulta')->find($request->get('pk'));
        if(!$obj)
            $this->createNotFoundException('No existe la consulta que está intentando editar');
        
        $obj->setTarifa($request->get('value'));
        
        $em->persist($obj);
        $em->flush();
        
        return new Response();
        
        
    }     
    
    
}

/*
 * 
 * 
 */

?>
