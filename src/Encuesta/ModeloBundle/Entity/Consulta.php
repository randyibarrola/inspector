<?php

namespace Encuesta\ModeloBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Encuesta\ModeloBundle\Entity\Inspeccion;

/**
 * 
 * @ORM\Table(name="consulta")
* @ORM\Entity
*
* @ORM\Entity(repositoryClass="Encuesta\ModeloBundle\Entity\ConsultaRepository")*
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
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $usuario;      
    
    /**
    * @var ArrayCollection $inspecciones
    *
    * @ORM\OneToMany(targetEntity="Inspeccion", mappedBy="consulta", cascade={"remove"})
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
     * @var integer
     *
     * @ORM\Column(name="porcentaje_mayor", type="integer", nullable=true)
     */
    private $porcentaje_mayor;    
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="promedio_mayor", type="decimal", nullable=true, scale=2)
     */
    private $promedio_mayor;        
    
    /**
     * @var integer
     *
     * @ORM\Column(name="porcentaje_menor", type="integer", nullable=true)
     */
    private $porcentaje_menor;     
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="promedio_menor", type="decimal", nullable=true, scale=2)
     */
    private $promedio_menor;            
    
    /**
     * @var integer
     *
     * @ORM\Column(name="porcentaje_igual", type="integer", nullable=true)
     */
    private $porcentaje_igual;    
    
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="promedio_igual", type="decimal", nullable=true, scale=2)
     */
    private $promedio_igual;   
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="aprobacion", type="decimal", nullable=true, scale=2)
     */
    private $aprobacion = 0;  
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="tarifa", type="decimal", nullable=true, scale=2)
     */
    private $tarifa = 0;      
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="adwords_id", type="string", length=100, nullable=true)
     */
    private $adwords_id;    
    
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
     * @var integer
     *
     * @ORM\Column(name="numero_ejecucion", type="integer", nullable=true)
     */
    private $numero_ejecucion = 0;        

    
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

    /**
     * Set porcentaje_mayor
     *
     * @param integer $porcentajeMayor
     * @return Consulta
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
     * @return Consulta
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
     * @return Consulta
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
    
    
    public function setPorcentajesDesdeEstados($estados){
        $total = count($estados);
        $cantidades = array('mejor'=>0, 'igual'=>0, 'peor'=>0);
        foreach ($estados as $estado){
            switch ($estado){
                case 1:
                    $cantidades['mejor'] ++;
                    break;
                case 0:
                    $cantidades['igual'] ++;
                    break;
                case -1:
                    $cantidades['peor'] ++;
                    break;  
                case -2:
                    $total --;
                    break; 
            }
        }
        
        $this->setPorcentajeMenor( $total > 0 ? (100 * $cantidades['mejor'])/$total : 0  );
        $this->setPorcentajeIgual( $total > 0 ?(100 * $cantidades['igual'])/$total : 0 );
        $this->setPorcentajeMayor( $total > 0 ?(100 * $cantidades['peor'])/$total : 0 );
    }
    
    public function setPromediosDesdePrecios($promedios){
        $total = count($promedios['menor']);
        $suma = 0;
        foreach($promedios['menor'] as $menor)
            $suma += $menor;
        $this->setPromedioMenor( $total > 0 ? ($suma)/$total : 0  );
        $total = count($promedios['igual']);
        $suma = 0;
        foreach($promedios['igual'] as $igual)
            $suma += $igual;
        $this->setPromedioIgual( $total > 0 ? ($suma)/$total : 0  );   
        $total = count($promedios['mayor']);
        $suma = 0;
        foreach($promedios['mayor'] as $mayor)
            $suma += $mayor;        
        $this->setPromedioMayor( $total > 0 ? ($suma)/$total : 0  );         
    }
    
    
    public function getPorcentajeMenorTexto(){
        //$valor = $this->promedio_menor > 0 ? ($this->promedio_igual - $this->promedio_menor) : 0;
        $valor = $this->promedio_menor ;
        return '-'.number_format($this->porcentaje_menor, 2, ',', '.').'% ( € '.number_format($valor, 2, ',', '.').' )';
    }
    
    public function getPorcentajeMayorTexto(){
        //$valor = $this->promedio_mayor > 0 ? ($this->promedio_mayor - $this->promedio_igual) : 0;
        $valor = $this->promedio_mayor;
        return '+'. number_format($this->porcentaje_mayor, 2, ',', '.').'% ( € '. number_format($valor, 2, ',', '.') .' )';
    }
    
    public function getPorcentajeIgualTexto(){
        return '='.number_format($this->porcentaje_igual, 2, ',', '.').'% ( € '.number_format($this->promedio_igual, 2, ',', '.').' )';
    }    
    
    public function getPorcentajeTexto(){
        return '-'.$this->porcentaje_menor.'%   ='.$this->porcentaje_igual.'%   +'.$this->porcentaje_mayor.'%';
    }
    
    

    /**
     * Set promedio_mayor
     *
     * @param string $promedioMayor
     * @return Consulta
     */
    public function setPromedioMayor($promedioMayor)
    {
        $this->promedio_mayor = $promedioMayor;

        return $this;
    }

    /**
     * Get promedio_mayor
     *
     * @return string 
     */
    public function getPromedioMayor()
    {
        return $this->promedio_mayor;
    }

    /**
     * Set promedio_menor
     *
     * @param string $promedioMenor
     * @return Consulta
     */
    public function setPromedioMenor($promedioMenor)
    {
        $this->promedio_menor = $promedioMenor;

        return $this;
    }

    /**
     * Get promedio_menor
     *
     * @return string 
     */
    public function getPromedioMenor()
    {
        return $this->promedio_menor;
    }

    /**
     * Set promedio_igual
     *
     * @param string $promedioIgual
     * @return Consulta
     */
    public function setPromedioIgual($promedioIgual)
    {
        $this->promedio_igual = $promedioIgual;

        return $this;
    }

    /**
     * Get promedio_igual
     *
     * @return string 
     */
    public function getPromedioIgual()
    {
        return $this->promedio_igual;
    }

    /**
     * Set usuario
     *
     * @param \Encuesta\ModeloBundle\Entity\Usuario $usuario
     * @return Consulta
     */
    public function setUsuario(\Encuesta\ModeloBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Encuesta\ModeloBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set aprobacion
     *
     * @param string $aprobacion
     * @return Consulta
     */
    public function setAprobacion($aprobacion)
    {
        $this->aprobacion = $aprobacion;

        return $this;
    }

    /**
     * Get aprobacion
     *
     * @return string 
     */
    public function getAprobacion()
    {
        return $this->aprobacion;
    }

    /**
     * Set tarifa
     *
     * @param string $tarifa
     * @return Consulta
     */
    public function setTarifa($tarifa)
    {
        $this->tarifa = $tarifa;

        return $this;
    }

    /**
     * Get tarifa
     *
     * @return string 
     */
    public function getTarifa()
    {
        return $this->tarifa;
    }

    /**
     * Set adwords_id
     *
     * @param string $adwordsId
     * @return Consulta
     */
    public function setAdwordsId($adwordsId)
    {
        $this->adwords_id = $adwordsId;

        return $this;
    }

    /**
     * Get adwords_id
     *
     * @return string 
     */
    public function getAdwordsId()
    {
        return $this->adwords_id;
    }

    /**
     * Set numero_ejecucion
     *
     * @param integer $numeroEjecucion
     * @return Consulta
     */
    public function setNumeroEjecucion($numeroEjecucion)
    {
        $this->numero_ejecucion = $numeroEjecucion;

        return $this;
    }

    /**
     * Get numero_ejecucion
     *
     * @return integer 
     */
    public function getNumeroEjecucion()
    {
        return $this->numero_ejecucion;
    }
}
