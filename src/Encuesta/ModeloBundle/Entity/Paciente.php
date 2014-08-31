<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Paciente
 *
 * @ORM\Table(name="paciente")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("cedula")
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\PacienteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Paciente 
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
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="pacientes")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade", nullable=true)
     **/
    private $categoria; 
    
    /**
     * @ORM\OneToMany(targetEntity="Imagen", mappedBy="paciente", cascade={"remove"})
     **/
    private $imagenes;
    
    /**
     * @ORM\OneToMany(targetEntity="Turno", mappedBy="paciente", cascade={"remove"})
     **/
    private $turnos;        


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
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cedula", type="integer", length=255, nullable=false)
     */
    private $cedula;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;   
    
    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=50, nullable=true)
     */
    private $celular; 
    
     /**
     * @var string
     *
     * @ORM\Column(name="imagen_cedula", type="string",length=255, nullable=true) 
     */
    private $imagen_cedula;

    
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
     * Constructor
     */
    public function __construct()
    {
        $this->imagenes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Paciente
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
     * @return Paciente
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
     * Set cedula
     *
     * @param integer $cedula
     * @return Paciente
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    
        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Paciente
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     * @return Paciente
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    
        return $this;
    }

    /**
     * Get celular
     *
     * @return string 
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set imagen_cedula
     *
     * @param string $imagenCedula
     * @return Paciente
     */
    public function setImagenCedula($imagenCedula)
    {
        $this->imagen_cedula = $imagenCedula;
    
        return $this;
    }

    /**
     * Get imagen_cedula
     *
     * @return string 
     */
    public function getImagenCedula()
    {
        return $this->imagen_cedula;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Paciente
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
     * @return Paciente
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
     * Set categoria
     *
     * @param \Encuesta\ModeloBundle\Entity\Categoria $categoria
     * @return Paciente
     */
    public function setCategoria(\Encuesta\ModeloBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Encuesta\ModeloBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add imagenes
     *
     * @param \Encuesta\ModeloBundle\Entity\Imagen $imagenes
     * @return Paciente
     */
    public function addImagene(\Encuesta\ModeloBundle\Entity\Imagen $imagenes)
    {
        $this->imagenes[] = $imagenes;
    
        return $this;
    }

    /**
     * Remove imagenes
     *
     * @param \Encuesta\ModeloBundle\Entity\Imagen $imagenes
     */
    public function removeImagene(\Encuesta\ModeloBundle\Entity\Imagen $imagenes)
    {
        $this->imagenes->removeElement($imagenes);
    }

    /**
     * Get imagenes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagenes()
    {
        return $this->imagenes;
    }
    
    public function getNombreCompleto()
    {
        return $this->nombre . ' '. $this->apellidos;
    }
    
    public function getRutaImagenCedula() {
        
        return $this->imagen_cedula ? '/uploads/paciente/'.$this->id.'/'.$this->imagen_cedula : '/images/no_disponible.jpg';
    }

    /**
     * Add turnos
     *
     * @param \Encuesta\ModeloBundle\Entity\Turno $turnos
     * @return Paciente
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
     * @return Paciente
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
