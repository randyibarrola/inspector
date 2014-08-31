<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Encuesta\ModeloBundle\Entity\Paciente;
use Encuesta\ModeloBundle\Entity\Turno;
use Encuesta\ModeloBundle\Entity\Medico;
use Encuesta\ModeloBundle\Entity\Turnero;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('ModeloBundle:Usuario')->findAll();
        $categorias = $em->getRepository('ModeloBundle:Categoria')->findAll();
        $pacientes = $em->getRepository('ModeloBundle:Paciente')->findAll();
        

        $stats = array(
            'usuarios' => count($usuarios),
            'categorias' => count($categorias),
            'pacientes' => count($pacientes)
           
        );

        return $this->render('DashboardBundle:Default:index.html.twig', array(
            'stats' => $stats
        ));
    }

    public function sideBarMenuAction()
    {
        return $this->render('DashboardBundle:Default:sideBarMenu.html.twig', array(
            'route_match' => $this->getRouteName()
        ));
    }

    private function getRouteName()
    {
        $request = Request::createFromGlobals();
        $router = $this->get('router');
        $router_match = $router->match($request->getPathInfo());

        return isset($router_match['_route']) ? $router_match['_route'] : '';
    }
    
    public function imprimirAction()
    {
        $em = $this->getDoctrine()->getManager(); 
        $turno = $em->getRepository('ModeloBundle:Turno')->find($this->getRequest()->get('id'));         
        
        if(!$turno)
            return $this->createNotFoundException('No existe el turno que esta intentando imprimir');
        
        $turnero = $em->getRepository('ModeloBundle:Turnero')->findTurneroDia();
        if(!$turnero) {
            $turnero = new Turnero();
            $turnero->setFecha(date('Ymd'));
            $em->persist($turnero);            
        }
        $numero = $turnero->getNumero() >= 99 ? 1 : $turnero->getNumero() + 1;
        
        $turnero->setNumero($numero);
        $em->persist($turnero);      
        
        $numero = $numero < 10 ? '0'.$numero : $numero;
        
        
        if(!$turno->getAsistencia()){
            $turno->setNumero($numero);
            $turno->setAsistencia(true);
            $turno->setHoraAsistencia(date('Hi'));
            $em->persist($turno);
            $em->flush();
        }
            
        return $this->render('DashboardBundle:Default:impresion.html.twig', array(
            'turno' => $turno
        ));        
    }    
    
    public function listTurnosAction()
    {
        $em = $this->getDoctrine()->getManager(); 
        $todos = $this->getRequest()->get("ver", null);
        
        $turnos = $todos ? $em->getRepository('ModeloBundle:Turno')->findAll() :$em->getRepository('ModeloBundle:Turno')->findTurnosDia(); 
        return $this->render('DashboardBundle:Default:listTurnos.html.twig', array(
            'list' => $turnos
        ));        
    }
    
    public function resetearTurneroAction()
    {
        $em = $this->getDoctrine()->getManager();        
        $turnero = $em->getRepository('ModeloBundle:Turnero')->findTurneroDia();
        
        if($this->getRequest()->getMethod() == "POST"){
            $valor = $this->getRequest()->get('valor');
            $turnero->setNumero($valor);
            $em->persist($turnero);
            $em->flush();
        }
        return $this->render('DashboardBundle:Default:resetearTurnero.html.twig', array(
            'turnero' => $turnero
        ));        
    }    
    
    public function cargaTurnosAction()
    {
        $em = $this->getDoctrine()->getManager();
  
        if($this->getRequest()->getMethod() == 'POST') {
                $dir = $this->container->getParameter('upload_dir').'turnos';
                if(!is_dir($dir))
                    mkdir($dir);              

                $tmp_name = $_FILES["turno"]["tmp_name"];
                $name = $_FILES["turno"]["name"];
                move_uploaded_file($tmp_name, $dir.$name);
                

                $row = 1;
                if (($handle = fopen($dir.$name, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        
                        $num = count($data);
                        
                        if($row > 1){
                            
                            for ($c=0; $c < $num; $c++) {                                
                                    var_dump($c);
                                    switch ($c){
                                        case 1:
                                           $paciente = $em->getRepository('ModeloBundle:Paciente')->findPacienteByCedula($data[$c]); 
                                           if(!$paciente) {
                                               $paciente = new Paciente();
                                               $paciente->setCedula($data[$c]);
                                           }

                                           $turno = new Turno();
                                           $turno->setPaciente($paciente);
                                           break; 
                                        case 2:
                                           $completo = $data[$c];
                                           $arreglo = explode(' ', $completo);

                                           if(!$paciente->getId()) {
                                               $paciente->setNombre( isset($arreglo[2]) ? $arreglo[2]. (isset($arreglo[3]) ? ' '.$arreglo[3] : '' )   : '' );
                                               $paciente->setApellidos(isset($arreglo[0]) ? $arreglo[0]. ( isset($arreglo[1]) ? ' '. $arreglo[1] : '' ): '' );
                                           }                                                               

                                           break;

                                        case 3:
                                           $turno->setFecha($data[$c]);                             

                                           break; 
                                        case 4:
                                           $turno->setHora($data[$c]);                             

                                           break;    
                                        case 5:
                                           $turno->setNombreMedico($data[$c]); 
                                           $medico = $em->getRepository('ModeloBundle:Medico')->findMedicoNombre($data[$c]);
                                           if(!$medico) {
                                               $completo = $data[$c];
                                               $arreglo = explode(' ', $completo);
                                               $medico = new Medico();
                                               $medico->setNombre( isset($arreglo[2]) ? $arreglo[2]. (isset($arreglo[3]) ? ' '.$arreglo[3] : '' )   : '' );
                                               $medico->setApellidos(isset($arreglo[0]) ? $arreglo[0]. ( isset($arreglo[1]) ? ' '. $arreglo[1] : '' ): '' );
                                           }

                                           $turno->setMedico($medico);

                                           break;
                                        case 6:
                                           $paciente->setCelular($data[$c]);                             

                                           break;  
                                        case 7:
                                           $paciente->setTelefono($data[$c]);                             

                                           break;  
                                        case 9:
                                           $turno->setEspecialidad($data[$c]);  
                                           if(!$medico->getId()) {
                                                $medico->setEspecialidad($data[$c]) ;
                                           }
                                           break;                                                           
                                    }                                 
                                
                                
                            }
                            $em->persist($medico);                                        
                            $em->persist($paciente);                                        
                            $em->persist($turno);
                            
                        }
                        $row++;
                            
                    }
                    fclose($handle);
                    
                }

                $em->flush();
                return $this->redirect($this->generateUrl('dashboard_turnos'));
                
        }
        return $this->render('DashboardBundle:Default:cargaTurnos.html.twig', array(
            
        ));        
    }
}
