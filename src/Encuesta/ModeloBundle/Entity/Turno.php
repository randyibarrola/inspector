<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Encuesta\ModeloBundle\Entity\Paciente;
use Encuesta\ModeloBundle\Entity\Medico;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\TurnoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Turno 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Paciente", inversedBy="turnos")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
     **/
    private $paciente; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Medico", inversedBy="turnos")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
     **/
    private $medico;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_medico", type="string", length=100, nullable=true)
     */
    private $nombre_medico;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="string", length=40, nullable=true)
     */
    private $fecha;
    
    /**
     * @var string
     *
     * @ORM\Column(name="hora", type="string", length=40, nullable=true)
     */
    private $hora; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="hora_asistencia", type="string", length=40, nullable=true)
     */
    private $hora_asistencia; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;        
    
    /**
     * @var string
     *
     * @ORM\Column(name="especialidad", type="string", length=40, nullable=true)
     */
    private $especialidad;  
    
    /**
     * @var string
     *
     * @ORM\Column(name="asistencia", type="boolean", nullable=true)
     */
    private $asistencia = false;      
    
    
  /**
   * @var datetime $created_at
   *
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(type="datetime", nullable=true)
   */   
    
  private $created_at;

  /**
   * @var datetime $updated_at
   *
   * @Gedmo\Timestampable(on="update")
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $updated_at;    
  


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre_medico
     *
     * @param string $nombreMedico
     * @return Turno
     */
    public function setNombreMedico($nombreMedico)
    {
        $this->nombre_medico = $nombreMedico;

        return $this;
    }

    /**
     * Get nombre_medico
     *
     * @return string 
     */
    public function getNombreMedico()
    {
        return $this->nombre_medico;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Turno
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set fecha
     *
     * @param string $fecha
     * @return Turno
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    
    public function getFechaVisual()
    {
        $fecha = substr($this->fecha, 6, 2).'/'.substr($this->fecha, 4, 2).'/'.substr($this->fecha, 0, 4);
        return $fecha;
    }    

    /**
     * Set hora
     *
     * @param string $hora
     * @return Turno
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }
    
    public function getHoraVisual()
    {
        if(strlen($this->hora) == 3)
            return '0'.substr($this->hora, 0, 1).':'.substr($this->hora, 1, 2);
        return substr($this->hora, 0, 2).':'.substr($this->hora, 2, 2);   
    }

    
    public function getHoraAsistenciaVisual()
    {
        if(strlen($this->hora_asistencia) == 3)
            return substr($this->hora_asistencia, 0, 1).':'.substr($this->hora_asistencia, 1, 2);
        return substr($this->hora_asistencia, 0, 2).':'.substr($this->hora_asistencia, 2, 2);   
    }    
    /**
     * Get hora
     *
     * @return string 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set especialidad
     *
     * @param string $especialidad
     * @return Turno
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;

        return $this;
    }

    /**
     * Get especialidad
     *
     * @return string 
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Turno
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Turno
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set cliente
     *
     * @param \Encuesta\ModeloBundle\Entity\Paciente $cliente
     * @return Turno
     */
    public function setCliente(\Encuesta\ModeloBundle\Entity\Paciente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Encuesta\ModeloBundle\Entity\Paciente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set medico
     *
     * @param \Encuesta\ModeloBundle\Entity\Medico $medico
     * @return Turno
     */
    public function setMedico(\Encuesta\ModeloBundle\Entity\Medico $medico = null)
    {
        $this->medico = $medico;

        return $this;
    }

    /**
     * Get medico
     *
     * @return \Encuesta\ModeloBundle\Entity\Medico 
     */
    public function getMedico()
    {
        return $this->medico;
    }

    /**
     * Set paciente
     *
     * @param \Encuesta\ModeloBundle\Entity\Paciente $paciente
     * @return Turno
     */
    public function setPaciente(\Encuesta\ModeloBundle\Entity\Paciente $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \Encuesta\ModeloBundle\Entity\Paciente 
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * Set hora_asistencia
     *
     * @param string $horaAsistencia
     * @return Turno
     */
    public function setHoraAsistencia($horaAsistencia)
    {
        $this->hora_asistencia = $horaAsistencia;

        return $this;
    }

    /**
     * Get hora_asistencia
     *
     * @return string 
     */
    public function getHoraAsistencia()
    {
        return $this->hora_asistencia;
    }

    /**
     * Set asistencia
     *
     * @param boolean $asistencia
     * @return Turno
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    /**
     * Get asistencia
     *
     * @return boolean 
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Turno
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
}
