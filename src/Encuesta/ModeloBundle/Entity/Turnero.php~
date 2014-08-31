<?php

namespace Encuesta\ModeloBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Encuesta\ModeloBundle\Entity\Turnero
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\TurneroRepository")* 
 * @ORM\Table(name="turnero")
 */
class Turnero
{
  /**
   * @var integer $id
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  
  /**
   * @ORM\Column(name="fecha", type="string", length=120)
   */
  private $fecha;  

  /**
   * @ORM\Column(name="numero", type="integer")
   */
  private $numero;

  

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
     * Set fecha
     *
     * @param string $fecha
     * @return Turnero
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

    /**
     * Set numero
     *
     * @param string $numero
     * @return Turnero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }
}
