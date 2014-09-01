<?php

namespace Encuesta\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Encuesta\DashboardBundle\Util\GestionBooking;

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
    
    
}

?>
