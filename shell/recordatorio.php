<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Magento Compiler Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shell_Compiler extends Mage_Shell_Abstract{

    public function run(){
        if ($this->getArg('blabla')) {

        }else if ($this->getArg('recordatorio')) {
            $this->recordatorio();
        }
        else{
            echo $this->usageHelp();
        }
    }

    public function recordatorio(){

	$collection = mage::getModel('customer/customer')->getCollection();
	 
	foreach ($collection as $clientes) {
		$clienteId = $clientes->getId();
		$cliente = mage::getModel('customer/customer')->load($clienteId);
		$fechaActual = date('Y-m-d');
		$fechaEvento=$cliente->getDob();
		$dias=$cliente->getSuffix();
		$fecha_diasRestados = date('Y-m-d', strtotime("$fechaEvento-$dias days")) ; // resta los dias introducidos por el cliente
		
		echo "fecha actual: $fechaActual";
		echo "fechaEvento: $fechaEvento";
		echo "fecha_diasRestados: $fecha_diasRestados";
		
		if($fecha_diasRestados== $fechaActual){
			$evento = $cliente->getPrefix();
			$to=$cliente->getEmail();
			$subject="Recuerda que en $dias dias tienes $evento ";
			$message="Saludos cordiales,\n\n Desde www.recuerdaeventos.com nos complace recordarle que proximamente tendra una celebracion,
				  si aun no sabe que comprar, puede echar un vistazo nuestra amplia gama de productos.\n\n Un saludo";
			if(mail ($to,$subject,$message))
				echo "Enviado con exito";
			echo"NO enviado";
		}	
	}  
	
	echo 'FIN EJECUCIÓN.'.PHP_EOL;
    }
    public function usageHelp(){

        return <<<USAGE
Usage:  php -f recordatorio.php -- [options]

  --recordatorio   Recoje todos los clientes, los filtra por la fecha del evento (que esta guardada en la fecha de cumpleaños del cliente) y les manda el email para recordarselo.

  info                          Show allowed indexers
  reindexall                    Reindex Data by all indexers
  help                          This help

  <indexer>     Comma separated indexer codes or value "all" for all indexers

USAGE;
    }
}

$shell = new Mage_Shell_Compiler();
$shell->run();
