<?php
namespace Encuesta\ModeloBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PacienteRepository extends EntityRepository
{
        public function findPacienteByTexto($texto) {
            $em = $this->getEntityManager();
            $consulta = $em->createQuery('
                      SELECT p FROM ModeloBundle:Paciente p 
                      WHERE p.nombre LIKE :texto 
                      OR p.apellidos LIKE :texto
                      OR p.cedula LIKE :texto
                      OR p.celular LIKE :texto		                   
                      ORDER BY p.id DESC
                    ');
            $consulta->setParameter('texto', '%' . $texto . '%');
            return $consulta->getResult();
        }
        
        public function findPacienteByCedula($cedula) {
            $em = $this->getEntityManager();
            $consulta = $em->createQuery('
                      SELECT p FROM ModeloBundle:Paciente p 
                      WHERE p.cedula LIKE :cedula                       
                    ');
            $consulta->setParameter('cedula',  $cedula );
            return $consulta->getOneOrNullResult();
        }        
}