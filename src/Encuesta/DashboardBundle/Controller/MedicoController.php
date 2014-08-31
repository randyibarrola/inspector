<?php
namespace Encuesta\DashboardBundle\Controller;

use Encuesta\ModeloBundle\Entity\Medico;
use Encuesta\ModeloBundle\Form\MedicoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class MedicoController extends Controller
{
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('ModeloBundle:Medico')->findAll();

        return $this->render('DashboardBundle:Medico:list.html.twig', array(
            'list' => $list            
        ));
    }


    public function viewAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('ModeloBundle:Medico')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el medico que está intentando ver');

        return $this->render('DashboardBundle:Medico:view.html.twig', array(
            'obj' => $obj
            
        ));
    }
    
    public function turnosAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('ModeloBundle:Medico')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el medico que está intentando ver');        
        

        return $this->render('DashboardBundle:Medico:turnos.html.twig', array(
            'list' => $obj->getTurnos(),
            'medico' => $obj
            
        ));
    }    

    public function deleteAction()
    {
        $response = $this->get('dashboard.ajaxresponse');
        $em = $this->getDoctrine()->getManager();
        $translator = $this->get('translator');

        try {
            $id = $this->getRequest()->get('id');

            
                $obj = $em->getRepository('ModeloBundle:Medico')->find($id);

                if(!$obj) {
                    $response->setHttpCode(500);
                    $response->setMessage($translator->trans('El medico que intenta eliminar no existe'));
                }
                else {
                    $em->remove($obj);
                    $em->flush();

                    $response->setMessage($translator->trans('El se ha medico eliminado satisfactoriamente'));
                    $response->setDataHolder(array('route' => $this->get('router')->generate('dashboard_medico')));
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

        $obj = new Medico();
        $form = $this->createForm(new MedicoType(), $obj);

        if($request->getMethod() == 'POST') {
            return $this->save($form);
        }

        return $this->render('DashboardBundle:Medico:form.html.twig', array(            
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
                if($data->getId() == null) {
                    $em->persist($data);
                    $em->flush();
                }

                $em->persist($data);
                $em->flush();                

                $response->setMessage($translator->trans('El médico se ha guardado satisfactoriamente'));
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

        $obj = $em->getRepository('ModeloBundle:Medico')->find($request->get('id'));
        if(!$obj)
            $this->createNotFoundException('No existe el medico que está intentando editar');


        $form = $this->createForm(new MedicoType(), $obj);

        if($request->getMethod() == 'POST') {

            $response = $this->save($form);

            return $response;
        }

        
        return $this->render('DashboardBundle:Medico:form.html.twig', array(
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