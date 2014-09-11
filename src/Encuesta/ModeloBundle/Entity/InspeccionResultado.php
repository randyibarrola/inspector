<?php

namespace Encuesta\ModeloBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Encuesta\ModeloBundle\Entity\Inspeccion;

/**
 * Inspeccion
 *
 * @ORM\Table(name="inspeccion_resultado")
 * @ORM\Entity
 */
class InspeccionResultado
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
     * @var Inspeccion
     *
     * @ORM\ManyToOne(targetEntity="Inspeccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inspeccion_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $inspeccion;    

    /**
     * @var string
     *
     * @ORM\Column(name="canal", type="string", length=70, nullable=false)
     */
    private $canal;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="precio", type="decimal",  nullable=false)
     */
    private $precio;  

    /**
     * @var date
     *
     * @ORM\Column(name="mejor", type="boolean", nullable=true)
     */
    private $mejor; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=70, nullable=false)
     */
    private $src;    
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set canal
     *
     * @param \DateTime $canal
     * @return InspeccionResultado
     */
    public function setCanal($canal)
    {
        $this->canal = $canal;

        return $this;
    }

    /**
     * Get canal
     *
     * @return \DateTime 
     */
    public function getCanal()
    {
        return $this->canal;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return InspeccionResultado
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set mejor
     *
     * @param boolean $mejor
     * @return InspeccionResultado
     */
    public function setMejor($mejor)
    {
        $this->mejor = $mejor;

        return $this;
    }

    /**
     * Get mejor
     *
     * @return boolean 
     */
    public function getMejor()
    {
        return $this->mejor;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return InspeccionResultado
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
     * @return InspeccionResultado
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
     * Set inspeccion
     *
     * @param \Encuesta\ModeloBundle\Entity\Inspeccion $inspeccion
     * @return InspeccionResultado
     */
    public function setInspeccion(\Encuesta\ModeloBundle\Entity\Inspeccion $inspeccion = null)
    {
        $this->inspeccion = $inspeccion;

        return $this;
    }

    /**
     * Get inspeccion
     *
     * @return \Encuesta\ModeloBundle\Entity\Inspeccion 
     */
    public function getInspeccion()
    {
        return $this->inspeccion;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return InspeccionResultado
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }
}
