<?php

namespace Encuesta\ModeloBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Encuesta\ModeloBundle\Entity\Inspeccion;

/**
* @ORM\Entity
* @ORM\Entity(repositoryClass="AEncuesta\ModeloBundle\Entity\ConsultaRepository")*
*/
class Consulta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;    
    
    /**
    * @var ArrayCollection $inspecciones
    *
    * @ORM\OneToMany(targetEntity="Inspeccion", mappedBy="consulta")
    */
    protected $inspecciones; 

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=70, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel", type="string", length=70, nullable=false)
     */
    private $hotel;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="canal_consulta", type="string", length=100, nullable=true)
     */
    private $canal_consulta;
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->inspecciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Consulta
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
     * Set hotel
     *
     * @param string $hotel
     * @return Consulta
     */
    public function setHotel($hotel)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return string 
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Consulta
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

    /**
     * Set canal_consulta
     *
     * @param string $canalConsulta
     * @return Consulta
     */
    public function setCanalConsulta($canalConsulta)
    {
        $this->canal_consulta = $canalConsulta;

        return $this;
    }

    /**
     * Get canal_consulta
     *
     * @return string 
     */
    public function getCanalConsulta()
    {
        return $this->canal_consulta;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Consulta
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
     * @return Consulta
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
     * Add inspecciones
     *
     * @param \Encuesta\ModeloBundle\Entity\Inspeccion $inspecciones
     * @return Consulta
     */
    public function addInspeccione(\Encuesta\ModeloBundle\Entity\Inspeccion $inspecciones)
    {
        $this->inspecciones[] = $inspecciones;

        return $this;
    }

    /**
     * Remove inspecciones
     *
     * @param \Encuesta\ModeloBundle\Entity\Inspeccion $inspecciones
     */
    public function removeInspeccione(\Encuesta\ModeloBundle\Entity\Inspeccion $inspecciones)
    {
        $this->inspecciones->removeElement($inspecciones);
    }

    /**
     * Get inspecciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInspecciones()
    {
        return $this->inspecciones;
    }
}