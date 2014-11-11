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
            'Relatorio\Controller\GuiaMovimento' 	 => 'Relatorio\Controller\GuiaMovimentoController',
            'Relatorio\Controller\ParcialDoHorario'  => 'Relatorio\Controller\ParcialDoHorarioController',
            'Relatorio\Controller\PulePremiada' 	 => 'Relatorio\Controller\PulePremiadaController',
            'Relatorio\Controller\Resultado' 		 => 'Relatorio\Controller\ResultadoController',
            'Relatorio\Controller\VendaPorDia' 		 => 'Relatorio\Controller\VendaPorDiaController',
            'Relatorio\Controller\VendaPorExtracao'  => 'Relatorio\Controller\VendaPorExtracaoController',
        ),
    ),

    'router' => array(
        'routes' => array(
        	'guiamovimento' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/relatorio/guiamovimento[/][/:action][/:key][page/:page]',
        			'defaults' => array(
        				'controller' => 'Relatorio\Controller\GuiaMovimento',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
            'parcialdohorario' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/relatorio/parcialdohorario[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\ParcialDoHorario',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
            'pulepremiada' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/relatorio/pulepremiada[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\PulePremiada',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
            'resultado' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/relatorio/resultado[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\Resultado',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
            'vendapordia' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/relatorio/vendapordia[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\VendaPorDia',
        				'action'     => 'index',
        				'key'	 => null,
        				'page' 		 => '1'
        			),
        		),
        	),
            'vendaporextracao' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/relatorio/vendaporextracao[/][/:action][/:key][page/:page]',
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\VendaPorExtracao',
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
            'relatorio/parcialdohorario/index' => __DIR__ . '/../view/relatorio/parcialdohorario/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'Relatorio' => __DIR__ . '/../view',
        ),
    ),
);
