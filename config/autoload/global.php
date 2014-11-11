<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$GLOBALS['pjb_config'] = array(
    'empresa' => 'PT',
    'sistema' => 'SJB',
    'versao' => '1.2',
    'loteria' => array(
                'lotep' =>      'Loteria do Estado - PB',
                'Federal' =>    'Federal - CX',
                'pt'    =>      'Para Todos',
                'ov'    =>      'Ouro Verde',
    ),
);

return array(

		'doctrine_config' => array(
				'driver' => 'pdo_pgsql',
				'user' => 'pjbmaster',
				'password' => 'method',
				'host' => '127.0.0.1',
				'dbname' => 'sjb0_9',
                'proxy_dir' => 'data/Proxy',
                'proxy_namespace' => 'DoctrineProxy'
		),                
		'service_manager' => array(
				'factories' => array(
// 						'Zend\Db\Adapter\Adapter'
// 						=> 'Zend\Db\Adapter\AdapterServiceFactory',
						'Navigation'
						=> 'Zend\Navigation\Service\DefaultNavigationFactory'
				),
		),
		'navigation' => array(
				'default' => array(
						array(
								'label' => 'Agencias',
								'route' => 'agencia',
								'pages' => array(
										array(
												'label' => 'Incluir',
												'route' => 'agencia',
												'action'=> 'add'
										)
								)
						),
						array(
								'label' => 'Rotas',
								'route' => 'rota',
								'pages' => array(
										array(
												'label' => 'Incluir',
												'route' => 'rota',
												'action'=> 'add'
										)
								)
		
						),
						array(
								'label' => 'Sair',
								'route' => 'application',
								'controller' => 'index',
								'action' => 'logout'
						)
				)
		),
);
