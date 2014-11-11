<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cadastro;

return array(
    'router' => array(
        'routes' => array(
        	'agencia' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/cadastro/agencia[/:action][/:key][page/:page]',
        			'defaults' => array(
        				'controller' => 'Cadastro\Controller\Agencia',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
        	'rota' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/cadastro/rota[/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Cadastro\Controller\Rota',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
        	'operador' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/cadastro/operador[/][/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Cadastro\Controller\Operador',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
        	'ponto' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/cadastro/ponto[/][/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Cadastro\Controller\Ponto',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
        	'terminal' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/cadastro/terminal[/][/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Cadastro\Controller\Terminal',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
        	'extracao' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/cadastro/extracao[/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Cadastro\Controller\Extracao',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),

        	'cliente' => array(
                'type' => 'segment',
                'options' => array(
                        'route'    => '/cadastro/cliente[/:action][/:key][page/:page]',
                        'defaults' => array(
                                'controller' => 'Cadastro\Controller\Cliente',
                                'action'     => 'index',
                                'key'	 => null,
                                'page' 		 => '1'
                        ),
                ),
        	),
            'tipojogo' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/cadastro/tipojogo[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Cadastro\Controller\TipoJogo',
                        'action'     => 'index',
                        'key'	 => null,
                        'page' 		 => '1'
                    ),
                ),
            ),
            'pule' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/cadastro/pule[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Cadastro\Controller\Pule',
                        'action'     => 'index',
                        'key'	 => null,
                        'page' 		 => '1'
                    ),
                ),
            ),
        		
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        	'Cadastro\Controller\Agencia' => 'Cadastro\Controller\AgenciaController',        	
        	'Cadastro\Controller\Ponto' => 'Cadastro\Controller\PontoController',
        	'Cadastro\Controller\Operador' => 'Cadastro\Controller\OperadorController',
        	'Cadastro\Controller\Rota' => 'Cadastro\Controller\RotaController',
        	'Cadastro\Controller\Terminal' => 'Cadastro\Controller\TerminalController',
        	'Cadastro\Controller\Extracao' => 'Cadastro\Controller\ExtracaoController',
            'Cadastro\Controller\TipoJogo' => 'Cadastro\Controller\TipoJogoController',
            'Cadastro\Controller\Cliente' => 'Cadastro\Controller\ClienteController',
            'Cadastro\Controller\Pule' => 'Cadastro\Controller\PuleController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'cadastro/agencia/index' => __DIR__ . '/../view/cadastro/agencia/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'Cadastro' => __DIR__ . '/../view',
        ),
    ),
);
