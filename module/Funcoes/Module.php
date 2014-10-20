<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Funcoes;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;
use Cadastro\Model\PontoTable;
use Cadastro\Model\Ponto;
use Cadastro\Model\OperadorTable;
use Cadastro\Model\Operador;
use Cadastro\Model\Agencia;
use Cadastro\Model\AgenciaTable;
use Cadastro\Model\RotaTable;
use Cadastro\Model\Rota;
use Cadastro\Model\TerminalTable;

class Module
{
	const DOCTRINE_BASE_PATH = '/../../vendor/doctrine/orm/lib/Doctrine';
	
    public function onBootstrap(MvcEvent $e)
    {
    	$e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $translationPath = realpath(__DIR__ . '/../../vendor/zendframework/zendframework/resources/languages');
        $translator = new Translator();
        $translator->addTranslationFile('phpArray', $translationPath . '/pt_BR/Zend_Validate.php',
 																'default', 'pt_BR');
        AbstractValidator::setDefaultTranslator($translator);
        
        $GLOBALS['sm'] = $e->getApplication()->getServiceManager();
        $this->initializeDoctrine2($e);
    }
    
    private function getDoctrine2Config($e)
    {
    	$config = $e->getApplication()->getConfig();
    	return $config['doctrine_config'];
    }
    
    private function initializeDoctrine2($e) {
    	$conn = $this->getDoctrine2Config($e);
    	$config = new Configuration();
    	$cache = new ArrayCache();
    	$config->setMetadataCacheImpl($cache);
    	$annotationPath	= realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/ORM/Mapping/Driver/DoctrineAnnotations.php');
    	AnnotationRegistry::registerFile($annotationPath);
    	$driver = new AnnotationDriver(
    			new AnnotationReader(),
    			array(__DIR__ . '/src/Jogar/Model')
    	);
//        $driver = new AnnotationDriver(
//    			new AnnotationReader(),
//    			array('/Cadastro/Model')
//    	);

    	$config->setMetadataDriverImpl($driver);
    	$config->setProxyDir($conn['proxy_dir']);
    	$config->setProxyNamespace($conn['proxy_namespace']);
    	$entityManager = EntityManager::create($conn, $config);
    	$GLOBALS['entityManager'] = $entityManager;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                	'Abstrato' => realpath(__DIR__ . '/../../vendor/abstratoframework/abstratoframework/library/Abstrato'),
                	'Doctrine\Common' => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/Common'),
                	'Doctrine\DBAL' => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/DBAL'),
                	'Doctrine\ORM' => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/ORM')
                ),
            ),
        );
    }
    
    public function getServiceConfig() {
    	return array(
    		'factories' => array(
    			

    		)
    	);
    }
}
