<?php

namespace Encuesta\ModeloBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Encuesta\ModeloBundle\Entity\Consulta;

/**
 * Inspeccion
 *
 * @ORM\Table(name="inspeccion")
 * @ORM\Entity
 */
class Inspeccion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var Consulta
     *
     * @ORM\ManyToOne(targetEntity="Consulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="consulta_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $consulta;  
    
    /**
     * @ORM\OneToMany(targetEntity="InspeccionResultado", mappedBy="inspeccion")
     */
    private $resultados;       

    /**
     * @var date
     *
     * @ORM\Column(name="fecha_ejecucion", type="date", length=70, nullable=false)
     */
    private $fecha_ejecucion;
    
    /**
     * @var date
     *
     * @ORM\Column(name="fecha_inicio", type="date", length=70, nullable=false)
     */
    private $fecha_inicio;  
    
    /**
     * @var date
     *
     * @ORM\Column(name="fecha_fin", type="date", length=70, nullable=false)
     */
    private $fecha_fin;  

    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;    

    /**
     * @var boolean
     *
     * @ORM\Column(name="ejecutada", type="boolean", length=70, nullable=true)
     */
    private $ejecutada=false; 
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estado_booking", type="integer", nullable=true)
     */
    private $estado_booking;   
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;     
    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resultados = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fecha_ejecucion
     *
     * @param \DateTime $fechaEjecucion
     * @return Inspeccion
     */
    public function setFechaEjecucion($fechaEjecucion)
    {
        $this->fecha_ejecucion = $fechaEjecucion;

        return $this;
    }

    /**
     * Get fecha_ejecucion
     *
     * @return \DateTime 
     */
    public function getFechaEjecucion()
    {
        return $this->fecha_ejecucion;
    }

    /**
     * Set fecha_inicio
     *
     * @param \DateTime $fechaInicio
     * @return Inspeccion
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fecha_inicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set fecha_fin
     *
     * @param \DateTime $fechaFin
     * @return Inspeccion
     */
    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;

        return $this;
    }

    /**
     * Get fecha_fin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set porcentaje_mayor
     *
     * @param integer $porcentajeMayor
     * @return Inspeccion
     */
    public function setPorcentajeMayor($porcentajeMayor)
    {
        $this->porcentaje_mayor = $porcentajeMayor;

        return $this;
    }

    /**
     * Get porcentaje_mayor
     *
     * @return integer 
     */
    public function getPorcentajeMayor()
    {
        return $this->porcentaje_mayor;
    }

    /**
     * Set porcentaje_menor
     *
     * @param integer $porcentajeMenor
     * @return Inspeccion
     */
    public function setPorcentajeMenor($porcentajeMenor)
    {
        $this->porcentaje_menor = $porcentajeMenor;

        return $this;
    }

    /**
     * Get porcentaje_menor
     *
     * @return integer 
     */
    public function getPorcentajeMenor()
    {
        return $this->porcentaje_menor;
    }

    /**
     * Set porcentaje_igual
     *
     * @param integer $porcentajeIgual
     * @return Inspeccion
     */
    public function setPorcentajeIgual($porcentajeIgual)
    {
        $this->porcentaje_igual = $porcentajeIgual;

        return $this;
    }

    /**
     * Get porcentaje_igual
     *
     * @return integer 
     */
    public function getPorcentajeIgual()
    {
        return $this->porcentaje_igual;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Inspeccion
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Inspeccion
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set consulta
     *
     * @param \Encuesta\ModeloBundle\Entity\Consulta $consulta
     * @return Inspeccion
     */
    public function setConsulta(\Encuesta\ModeloBundle\Entity\Consulta $consulta = null)
    {
        $this->consulta = $consulta;

        return $this;
    }

    /**
     * Get consulta
     *
     * @return \Encuesta\ModeloBundle\Entity\Consulta 
     */
    public function getConsulta()
    {
        return $this->consulta;
    }

    /**
     * Add resultados
     *
     * @param \Encuesta\ModeloBundle\Entity\InspeccionResultado $resultados
     * @return Inspeccion
     */
    public function addResultado(\Encuesta\ModeloBundle\Entity\InspeccionResultado $resultados)
    {
        $this->resultados[] = $resultados;

        return $this;
    }

    /**
     * Remove resultados
     *
     * @param \Encuesta\ModeloBundle\Entity\InspeccionResultado $resultados
     */
    public function removeResultado(\Encuesta\ModeloBundle\Entity\InspeccionResultado $resultados)
    {
        $this->resultados->removeElement($resultados);
    }

    /**
     * Get resultados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResultados()
    {
        return $this->resultados;
    }

    /**
     * Set ejecutada
     *
     * @param boolean $ejecutada
     * @return Inspeccion
     */
    public function setEjecutada($ejecutada)
    {
        $this->ejecutada = $ejecutada;

        return $this;
    }

    /**
     * Get ejecutada
     *
     * @return boolean 
     */
    public function getEjecutada()
    {
        return $this->ejecutada;
    }

    /**
     * Set estado_booking
     *
     * @param integer $estadoBooking
     * @return Inspeccion
     */
    public function setEstadoBooking($estadoBooking)
    {
        $this->estado_booking = $estadoBooking;

        return $this;
    }

    /**
     * Get estado_booking
     *
     * @return integer 
     */
    public function getEstadoBooking()
    {
        return $this->estado_booking;
    }
    
    public function __toString() {
        return '$this->fecha_ejecucion;';
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Inspeccion
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
}
