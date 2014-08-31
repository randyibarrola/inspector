<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Medico
 *
 * @ORM\Table(name="medico")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\MedicoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Medico 
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="especialidad", type="string", length=50, nullable=true)
     */
    private $especialidad;   
    
    /**
     * @var string
     *
     * @ORM\Column(name="consultorio", type="string", length=50, nullable=true)
     */
    private $consultorio; 
    
     /**
     * @var string
     *
     * @ORM\Column(name="pasillo", type="string", length=255, nullable=true) 
     */
    private $pasillo;
    
    /**
     * @ORM\OneToMany(targetEntity="Turno", mappedBy="medico", cascade={"remove"})
     **/
    private $turnos;    

    
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
     * Set nombre
     *
     * @param string $nombre
     * @return Medico
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Medico
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
     * Set especialidad
     *
     * @param string $especialidad
     * @return Medico
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
     * Set consultorio
     *
     * @param string $consultorio
     * @return Medico
     */
    public function setConsultorio($consultorio)
    {
        $this->consultorio = $consultorio;
    
        return $this;
    }

    /**
     * Get consultorio
     *
     * @return string 
     */
    public function getConsultorio()
    {
        return $this->consultorio;
    }

    /**
     * Set pasillo
     *
     * @param string $pasillo
     * @return Medico
     */
    public function setPasillo($pasillo)
    {
        $this->pasillo = $pasillo;
    
        return $this;
    }

    /**
     * Get pasillo
     *
     * @return string 
     */
    public function getPasillo()
    {
        return $this->pasillo;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Medico
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
     * @return Medico
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
    
        
    public function getNombreCompleto()
    {
        return $this->nombre . ' '. $this->apellidos;
    }
    
    public function getNombreParaBusqueda() 
    {
        return strtoupper($this->apellidos. ' '.$this->nombre) ;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turnos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add turnos
     *
     * @param \Encuesta\ModeloBundle\Entity\Turno $turnos
     * @return Medico
     */
    public function addTurno(\Encuesta\ModeloBundle\Entity\Turno $turnos)
    {
        $this->turnos[] = $turnos;

        return $this;
    }

    /**
     * Remove turnos
     *
     * @param \Encuesta\ModeloBundle\Entity\Turno $turnos
     */
    public function removeTurno(\Encuesta\ModeloBundle\Entity\Turno $turnos)
    {
        $this->turnos->removeElement($turnos);
    }

    /**
     * Get turnos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTurnos()
    {
        return $this->turnos;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Medico
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}
