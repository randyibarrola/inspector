<?php
namespace Encuesta\DashboardBundle\Util;

//use Robot\ModeloBundle\Entity\Cookie;
use Encuesta\DashboardBundle\Util\SimpleHtmlDom;
use Doctrine\ORM\EntityManager;
//use Ganon\HTML_Parser_HTML5;

class GestionBooking
{
    public static function str_get_dom($str, $return_root = true) {
            $a = new HtmlParserHtml5($str);
            return (($return_root) ? $a->root : $a);
    }
    public static function file_get_dom($file, $return_root = true, $use_include_path = false, $context = null) {
            if (version_compare(PHP_VERSION, '5.0.0', '>='))
                    $f = file_get_contents($file, $use_include_path, $context);
            else {
                    if ($context !== null)
                            trigger_error('Context parameter not supported in this PHP version');
                    $f = file_get_contents($file, $use_include_path);
            }
            return (($f === false) ? false : str_get_dom($f, $return_root));
    }
    public static function dom_format(&$root, $options = array()) {
            $formatter = new HTML_Formatter($options);
            return $formatter->format($root);
    }
    
    public static function LoginAtrapalo($url)
    {
        //$username = $producto->getUsuarioPortales();
        //$password = base64_decode($producto->getPasswordPortales());
        //$username = 'escaleralavapies';
        //$password = 'STRlavapies';
        $loginUrl = $url;
        $http_headers = array(                    
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/30.0.2',
                    'Accept: */*',
                    'Accept-Language: en-us,en;q=0.5',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'X-Requested-With: XMLHttpRequest',
                    'Connection: keep-alive'
                  );
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, 'user='.$username.'&pass='.$password);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)'); 

        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //curl_exec($ch);
        
        return $ch;
    }
    
    
    public static function getContenido($url)
    {
        $http_headers = array(                    
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
                    'Accept: */*',
                    'Accept-Language: en-us,en;q=0.5',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'Connection: keep-alive'
                  );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
        
        return $ch;
    }

    
    
    public static function ObtenerDatosBooking($url)
    {
        
        $ch = self::LoginAtrapalo($url);
        $content = curl_exec($ch);       
        
//        $html = self::str_get_dom($content);   
        $html = SimpleHtmlDom::my_file_get_html($content);
                
        //Obteniendo el nombre del hotel
        $arrayNombre = $html->find('span[id="hp_hotel_name"]', 0);
        
//        ob_implicit_flush(true);
        $nombreHotel = $arrayNombre->plaintext;
        
        $arrayDireccion = $html->find('p[class="address"] span', 0);
        $direccion = $arrayDireccion->plaintext;
        $coords = $arrayDireccion->getAttribute('data-coords');
        
        //$direccion = $arrayDireccion[0]->getPlainTextUTF8();
        
        // Descripcion
        $arrayDescripcionLarga = $html->find('div[id="summary"]', 0);
        $arrayDescripcionCorta = $html->find('div[id="hotel_main_content"] p[class="summary"]', 0);
        
        
        $descripcionLarga = isset($arrayDescripcionLarga) ? $arrayDescripcionLarga->plaintext : '';
        $descripcionCorta = isset($arrayDescripcionCorta) ? $arrayDescripcionCorta->plaintext : '';
        
        $arrayClasificacion = $html->find('a[class="big_review_score_detailed ind_rev_total hp_review_score"]', 0);        
        $clasificacion = $arrayClasificacion->plaintext;
        
        //Obteniendo los servicios
//        $arrayServiciosNombre = $html->find('div[class="nha_single_unit_facilities common_room_facilities"] h3');
//        $arrayServiciosDescrp = $html->find('div[class="nha_single_unit_facilities common_room_facilities"] p');
        $servicios = array();
        
        $arrayServicios = $html->find('div[class="common_room_facilities"] div[class="description"]');
        foreach ($arrayServicios as $serv)
        {
            $childrens = $serv->children();
            $nombreServ = '';
            $descpServ = '';
            foreach ($childrens as $ch)
            {
                if ($ch->tag == 'h3')
                    $nombreServ = $ch->plaintext;
                else if ($ch->tag == 'p')
                    $descpServ = $ch->plaintext;
                    
            }
            
            $servicios[] = array(
                'nombre' => $nombreServ,
                'descripcion' => $descpServ
            );
        }
        
        //var_dump($arrayServiciosDescrp);exit;
//        foreach ($arrayServiciosNombre as $sn)
//        {
//            $value = trim($sn->plaintext);
//            if ( !empty($value) )
//            {
//                $servicios[] = array(
//                    'nombre' => $value,
//                    'descripcion' => ''
//                );
//            }
//        }
//        
//        $c = 0;
//        foreach ($arrayServiciosDescrp as $sd)
//        {
//            $servicios[$c]['descripcion'] = $sd->plaintext;
//            $c++;
//        }        
        
//        var_dump($servicios);exit;
        
        
        // Obteniendo las condiciones
        $arrayCondicionesNombre = $html->find('div[id="hotelPoliciesInc"] h3 span');
        $arrayCondicionesDescripcion = $html->find('div[id="hotelPoliciesInc"] div[class="description"]');
        
        $condiciones = array();
        
        foreach ($arrayCondicionesDescripcion as $cond)
        {            
            $nombre = '';
            $descripcion = '';
            $children = $cond->children();
            foreach ($children as $child)
            {
                if ($child->tag == 'h3')
                    $nombre = $child->plaintext;
                else if ($child->tag == 'p')
                    $descripcion .= $child->plaintext;
            }
                
            /*$h3 = $cond->getChildrenByTag('<h3>');
            var_dump($h3);
            $descr = '';
            $ps = $cond->getChildrenByTag('<p>');
            var_dump($ps);exit;
            foreach ($ps as $p)
                $ps .= $p->getPlainTextUTF8();*/
            
            $condiciones[] = array(
                'nombre' => $nombre,
                'descripcion' => $descripcion
            );
        }        
       
        // Comentarios
        $arrayUserProfile = $html->find('li[class="review_tr"] div[class="review_user_type"]');
        $arrayComentarios = $html->find('li[class="review_tr"] div[class="cell_comments"]');
        $arrayScore = $html->find('li[class="review_tr"] div[class="cell_score"]');
        $comentarios = array();
        
        $pos = 0;
        foreach ($arrayComentarios as $comment)
        {            
            $nombre = '-';
            $pais = '-';
            $good = '-';
            $bad = '-';
            $fecha = '-';
            $motivo = '-';
            $children = $comment->children();
            foreach ($children as $child)
            {
                if ($child->tag == 'div')
                {
                    $fullText = '';
                    $childs = $child->children();
                    foreach ($childs as $ch)
                    {
                        if ($ch->tag == 'p' &&  $ch->getAttribute('class') && $ch->getAttribute('class') == 'comments_good' )
                            $good = $ch->plaintext;
                        else if ($ch->tag == 'p' && $ch->getAttribute('class') && $ch->getAttribute('class') == 'comments_bad')
                            $bad = $ch->plaintext;
                        
                    }
                    
                }
                
            } 
            
            if (isset($arrayUserProfile[$pos]))
            {
                $userChilds = $arrayUserProfile[$pos]->children();
                foreach ($userChilds as $user)
                {
                    if ($user->getAttribute('class') == 'cell_user_name')
                        $nombre = $user->plaintext;
                    else if ($user->getAttribute('class') == 'cell_user_profile')
                        $motivo = $user->plaintext;
                    else if ($user->getAttribute('class') == 'user_location')
                        $pais = $user->plaintext;
                    else if ($user->getAttribute('class') == 'cell_user_date')
                        $fecha = $user->plaintext;
                }
            }
            
            
            $comentarios[] = array(
                'nombre' => $nombre,
                'good' => $good,
                'bad' => $bad, 
                'fecha' => $fecha,   
                'motivo' => $motivo,
                'pais' => $pais,
                'score' => $arrayScore[$pos]->plaintext
            );
            
            $pos++;
        } 
        
        
           
        
         // Imagenes
        $arrayImagenes = $html->find('div[id="photos_distinct"] a[class="hotel_thumbs_sprite"]');
        $imagenes = array();
        
        foreach ($arrayImagenes as $image)
        {
            $imagenes[] = array(
                'miniatura' => $image->getAttribute('href'),
                'portada'   => $image->getAttribute('data-resized'),
                
            ); 
        }
       
        //Puntuacion
        $arrayPuntuacion = $html->find('div[class="hotel_large_photp_score"] span[class="average"]', 0);
        $arrayCantCom = $html->find('div[class="hotel_large_photp_score"] strong[class="count"]', 0);
        
        
        
        $puntuacion = array(
            'puntuacion' => $arrayPuntuacion->plaintext,
            'cantidad_comentarios'   => $arrayCantCom->plaintext,
        ); 
        
        
        // Tipos de habitacion
        $arrayTiposHab = $html->find('table[id="maxotel_rooms"] tr');
        $tiposHabitacion = array();
        foreach ($arrayTiposHab as $th)
        {
            $nombre = '';
            $maxOcupacion = '';
            $childrens = $th->children();
            foreach ($childrens as $ch)
            {
                if ($ch->getAttribute('class') == 'ftd')
                    $nombre = $ch->plaintext;
                else if ($ch->getAttribute('class') == 'occ_no_dates')
                {
                    $childs = $ch->children(); 
                    foreach ($childs as $c)
                    {
                        if ($c->tag == 'img')
                        {
                            $class = $c->getAttribute('class');
                            $parts = explode(' ', $class);                            
                            $maxOcupacion = str_replace('max', '', $parts[1]);
                        }
                        
                    }
                    
                }
                    
            }
            
            if (!empty($nombre) && !empty($maxOcupacion) )
            {
                $tiposHabitacion[] = array(
                    'nombre' => $nombre,
                    'maximo_ocupacion' => $maxOcupacion

                ) ;
            }
        } 
        
        
        
        $result = array(
            'nombre' => $nombreHotel, 
            'total_apartamentos' => $descripcionCorta,
            'descripcion' => $descripcionLarga,
            'clasificacion' => $clasificacion,
            'direccion' => $direccion,
            'coordenadas' => $coords, 
            'servicios' => $servicios, 
            'condiciones' => $condiciones,
            'opinion' => $comentarios,
            'imagen' => $imagenes,
            'puntuacion' => $puntuacion,
            'habitaciones' => $tiposHabitacion
        );       
        
        
        
        return  $result;
        
        
        
    }
    
    public static function ObtenerTarifas(Producto $producto, $fechaInicio, $fechaFin, $moneda, $em)
    {
        $url = $producto->getUrl();
        $cookies = $em->getRepository('ModeloBundle:Cookie')->findAll();
        $cookie = count($cookies) > 0 ? $cookies[0] : null;
        
        if (!is_object($cookie) || (time() - $cookie->getUpdatedAt()->getTimestamp() > 86400) )
        {            
           
            // Haciendo un curl a la url para obtener la cookie que nos da booking.com y salvarla en cookies.txt
            $cookies = getcwd() . "/cookies.txt";
            $http_headers = array(                    
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
                    'Accept: */*',
                    'Accept-Language: en-us,en;q=0.5',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'Connection: keep-alive'
                  );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);            
            curl_setopt($ch, CURLOPT_COOKIESESSION, true );
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.12) Gecko/2009070611 Firefox/3.0.12");
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
            //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)'); 

            $content = curl_exec($ch); 
            curl_close($ch);
            
            $html = SimpleHtmlDom::my_file_get_html($content);
            $a = $html->find('input[name=error_url]', 0);
            $errorUrl = $a->value;
            $arr = explode('?', $errorUrl);                
            $arr = explode(';', $arr[1]);            
            // Este es el ID que nos identificara temporalmente en booking.com
            $sid = $arr[0];
            
            if (!is_object($cookie))
                $cookie = new Cookie();
            
            $cookie->setSid($sid);
            $em->persist($cookie);
            $em->flush();
            
            unlink($cookies);
            //$session->set("sid", $sid);     
            
        }
        
        
        
        $fechaInicioArr = explode('-', $fechaInicio);            
        $fechaFinArr = explode('-', $fechaFin);
        $sid = $cookie->getSid();//$session->get("sid");
        $url = $producto->getUrl().'?'.$sid.'&dcid=1&tab=1&origin=hp&do_availability_check=on&checkin_monthday='.intval($fechaInicioArr[2]).'&checkin_year_month='.$fechaInicioArr[0].'-'.intval($fechaInicioArr[1]).'&checkout_monthday='.intval($fechaFinArr[2]).'&checkout_year_month='.$fechaFinArr[0].'-'.intval($fechaFinArr[1]).'&selected_currency='.$moneda;
//        $url = 'http://robot.local/tarf.html';
        $http_headers = array(                    
                    'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
                    'Accept: */*',
                    'Accept-Language: en-us,en;q=0.5',
                    'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                    'Connection: keep-alive'
                  );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);            
        curl_setopt($ch, CURLOPT_COOKIESESSION, true );
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.12) Gecko/2009070611 Firefox/3.0.12");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)'); 

        $content = curl_exec($ch); 
        curl_close($ch);

        $html = SimpleHtmlDom::my_file_get_html($content);
        $table = $html->find('table[id=maxotel_rooms]', 0);

        $tiposHabitaciones = array();
        
        $tHs = $html->find('td[class=roomType] a[class=togglelink]');
        $extra = $html->find('td[class=roomType] div[class=small]');
        
        
        $pos = 1;
        foreach ($tHs as $th)
        {    
            $divs = $html->find('tr[class=room_loop_counter'.$pos.'] div[class=ico_policy_info]');
            //$quitar = $html->find('tr[class=room_loop_counter'.$pos.'] div[class=ico_policy_info] div[class=differing_policies]');

            $precioAnterior = $html->find('tr[class=room_loop_counter'.$pos.'] td[class=roomPrice] span[class=rackrate]');
            $precioActual = $html->find('tr[class=room_loop_counter'.$pos.'] td[class=roomPrice] strong');
            $caracteristicas = $html->find('tr[class=room_loop_counter'.$pos.'] span[class=highlighted_facilities_reinforcement] span');
            
            $detalle = $html->find('tr[class=room_loop_counter'.$pos.'] td div[class=blocktoggle]', 0);
            $imgs = $detalle->find('img');
            $imagenes = array();
            
            foreach ($imgs as $img)
            {
                $imagenes[] = $img->src;
            }
            

            $j = 0;
            $modificadores = array();
            foreach ($divs as $div)
            {
                //$condicion = str_replace($quitar[$j]->plaintext, '', $div->plaintext).' <br/> ';
                $conditions = array();
                $conds = $div->find('span[!style]');
                                
                for ($i = 0; $i < count($conds); $i++) 
                {
                    $text = $conds[$i]->plaintext;
                    $filtro = trim(str_replace('&bull;', '', $text));
                    if (!empty($filtro))                    
                        $conditions[] = $filtro;                    
                }
                    
                $symbols = array("&#x20AC;", "&#163;", "US$", "€", "£");
                $pAnt = isset($precioAnterior[$j]) ? trim(str_replace($symbols, "", $precioAnterior[$j]->plaintext)) : '';
                $pAct = trim(str_replace($symbols, "", $precioActual[$j]->plaintext));
                $modificador = array(
                    'condiciones' => $conditions,
                    'precioAnterior' => $pAnt,
                    'precioActual' => $pAct
                );
                $modificadores[] = $modificador;
                $j++;
            }
            
            $caracts = array();
            foreach ($caracteristicas as $car)
                $caracts[] = $car->plaintext;

            $tiposHabitaciones[] = array(
                'nombre' => $th->plaintext,
                'extra' => $extra[$pos-1]->plaintext,
                'caracteristicas' => $caracts,
                'condiciones' => $modificadores,
                'descripcion' => $detalle->plaintext,
                'imagenes' => $imagenes
                
            );
            
            $pos++;
        }
        
        
        return $tiposHabitaciones;
        
        // Esto es para imprimir y ver que devuelve
        
//        foreach ($tiposHabitaciones as $thab)
//        {
//            echo $thab['nombre'];
//            echo $thab['extra'];
//            var_dump($thab['caracteristicas']);
//            echo 'Condiciones';
//            var_dump($thab['condiciones']);
//        }
//        exit;
        
    }
    
    public static function getResultadosTrivago($url, $fecha_entrada, $fecha_salida){
            
            
            //$fechaArr = null;
            //$fechaDep = null;
            $geo = null;
            $ipath = null;
            
            $url = str_replace('%5B', '[', $url);
            $url = str_replace('%5D', ']', $url);
           
            
            /*if(substr_count($url, 'aDateRange[arr]') > 0){
                $posicionArr = stripos($url, 'aDateRange[arr]');
                $fechaArr = substr($url, $posicionArr+16, 10);
            }
            
            if(substr_count($url, 'aDateRange[dep]') > 0){
                $posicionArr = stripos($url, 'aDateRange[dep]');
                $fechaDep = substr($url, $posicionArr+16, 10);
            } */   
            
            if(substr_count($url, 'iGeoDistanceItem') > 0){
                $posicionArr = stripos($url, 'iGeoDistanceItem');
                $txtgeo = substr($url, $posicionArr+17, 6);
                $geo = preg_replace("/[^0-9]/", "", $txtgeo);    
               
            }  
            
            if(substr_count($url, 'iPathId') > 0){
                $posicionArr = stripos($url, 'iPathId');
                $ipath = substr($url, $posicionArr+8, 5);
            }             
            
            $dealUrl = 'http://www.trivago.es/search/slideout/deals/0/1/iGeoDistanceItem/iGeoDistanceItem/?iPathId=ipathid&aDateRange[arr]=arrive&aDateRange[dep]=departure&iRoomType=7&&_=1409965536717';
            $dealUrl = str_replace('iGeoDistanceItem', $geo, $dealUrl);
            $dealUrl = str_replace('ipathid', $ipath, $dealUrl);
            //$dealUrl = str_replace('arrive', $fechaArr,$dealUrl );
            //$dealUrl = str_replace('departure', $fechaDep, $dealUrl );
            
            $dealUrl = str_replace('arrive', $fecha_entrada,$dealUrl );
            $dealUrl = str_replace('departure', $fecha_salida, $dealUrl );           
            
            
            //if($fechaArr && $fechaDep) {
            if($fecha_entrada && $fecha_salida) {
                $intervalos = array();
                $cantidadBooking = 0;                 
                $resultados = self::getComparativaTrivago($dealUrl, $geo);
               
                /*if($resultados['viable']){
                    $cantidadBooking++;
                }
                $intervalos['hotel'] = $resultados['hotel'];
                $intervalos[1] = array( 'fecha_inicio' => $fechaArr , 'fecha_fin' => $fechaDep , 'resultados' => $resultados, 'url'=>$url);            

                for($i = 2; $i < 7; $i++){                    
                    $date = new \DateTime($fechaDep);
                    $date->add(new \DateInterval('P1D'));
                    //tranformando url
                    $url = str_replace($fechaArr, $date->format('Y-m-d'), $url);
                    $fechaArr = $date->format('Y-m-d');

                    $date = new \DateTime($fechaDep);
                    $date->add(new \DateInterval('P7D')); 
                    //tranformando url
                    $url = str_replace($fechaDep, $date->format('Y-m-d') , $url);
                    $fechaDep = $date->format('Y-m-d');
                    
                    $resultados = self::getComparativaTrivago($url);
                    if($resultados['viable']){
                        $cantidadBooking++;
                    }
                    $intervalos[$i] = array('fecha_inicio' => $fechaArr, 'fecha_fin' => $fechaDep, 'resultados' => $resultados, 'url'=>$url );
                }     
                
                $intervalos['porcentaje'] = number_format( ( $cantidadBooking * 100 ) / 12, 2 );*/
                
                //return $intervalos;
                return $resultados;
            }
            
            return array();
    }
    
    public static function getComparativaTrivago($url, $geo = null)
    {
        $ch = self::LoginAtrapalo($url);
        $content = curl_exec($ch); 
        curl_close($ch);
        
        $contenido = json_decode($content);
        
        //$hotel = $contenido->hotels[0];
        $comparativa = $contenido->$geo->sHtml;
        
        $busqueda = array();
        $busqueda['mejor'] = array();
        $busqueda['canales'] = array();
        $busqueda['precios'] = false;
        
        //$html = self::str_get_dom($content);   
        //echo $contenido->hotels[0]->html;die;
        $html = SimpleHtmlDom::my_file_get_html($comparativa);  
        
        $canales = $html->find('div[class=deals_logo_wrapper] img'); 
        $precios = $html->find('strong[class=price price_info]');
        $existentes = array();
        
        foreach($canales as $key => $canal){ 
           if(!in_array($canal->title,$busqueda['canales'])){
            $busqueda['canales'][] = $canal->title;
            $busqueda['src'][] = $canal->src;               
           } else {
              $existentes[]=$key; 
           }

        }
        
        foreach($precios as $key => $precio){
            if(!in_array($key, $existentes)){
                $p = str_replace(' â‚¬', '', $precio->plaintext); 
                $p = str_replace('â‚¬', '', $precio->plaintext);
                $p = str_replace(' €', '', $precio->plaintext); 
                $p = str_replace('€', '', $precio->plaintext); 
                $p = str_replace('&nbsp;', 0, $precio->plaintext);
                $busqueda['precios'][] = trim($p ); 
            }
            
        }
        
        
        /*
        
        foreach($strongs as $st){            
            $busqueda['mejor'][] = trim(  $st->plaintext );
            if( substr_count(strtolower( trim(  $st->plaintext ) ) , 'booking.com') ){
               $busqueda['viable'] = true;
            }
        }
        */
        /*
        $contenedorCanales = $html->find('div[class=item_prices] div[class=item_main]', 0);
        $canales = $contenedorCanales->find('li[class=single_price]');
        foreach($canales as $key => $canal){
            $nombre = $canal->find('em', 0);
            $busqueda['otros'][$key] = array();
            $busqueda['otros'][$key]['nombre'] = $nombre->plaintext;            
            $precio = $canal->find('strong', 0);
            $busqueda['otros'][$key]['precio'] = $precio->plaintext;   

            //echo $nombre.'  '.$precio."\n";
        }
        //$busqueda['hotel'] = trim($html->find('h3[class=jsheadline]',0)->plaintext);
        */
        
        return $busqueda;
    }
    
    
}
