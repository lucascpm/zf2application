<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Jogar;

return array(
    'controllers' => array(
        'invokables' => array(
            'Jogar\Controller\NovoJogo' 		=> 'Jogar\Controller\NovoJogoController',
            'Jogar\Controller\CancelarJogo' 	=> 'Jogar\Controller\CancelarJogoController',
            'Jogar\Controller\PreDatarJogo' 	=> 'Jogar\Controller\PreDatarJogoController',
            'Jogar\Controller\RepetirJogo' 	    => 'Jogar\Controller\RepetirJogoController',
            'Jogar\Controller\Login' 	        => 'Jogar\Controller\LoginController',
        ),
    ),

    'router' => array(
        'routes' => array(
        	'novojogo' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/jogar/novojogo[/][/:action][/:key][page/:page]',
        			'defaults' => array(
        				'controller' => 'Jogar\Controller\NovoJogo',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
        	'cancelarjogo' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/jogar/cancelarjogo[/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Jogar\Controller\CancelarJogo',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
        	'predatarjogo' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/jogar/predatarjogo[/][/:action][/:key][page/:page]',
        					'defaults' => array(
        							'controller' => 'Jogar\Controller\PreDatarJogo',
        							'action'     => 'index',
        							'key'	 => null,
        							'page' 		 => '1'
        					),
        			),
        	),
            'repetirjogo' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/jogar/repetirjogo[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Jogar\Controller\RepetirJogo',
                        'action'     => 'index',
                        'key'	 => null,
                        'page' 		 => '1'
                    ),
                ),
            ),
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/jogar/login[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Jogar\Controller\Login',
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

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'jogar/novojogo/index' => __DIR__ . '/../view/jogar/novojogo/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'Jogar' => __DIR__ . '/../view',
        ),
    ),
);
