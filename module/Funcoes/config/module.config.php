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
            'Funcoes\Controller\TrocarSenha' 		=> 'Funcoes\Controller\TrocarSenhaController',
        ),
    ),

    'router' => array(
        'routes' => array(
        	'trocarsenha' => array(
        		'type' => 'segment',
        		'options' => array(
        			'route'    => '/funcoes/trocarsenha[/][/:action][/:key][page/:page]',
        			'defaults' => array(
        				'controller' => 'Funcoes\Controller\TrocarSenha',
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
            'funcoes/trocarsenha/index' => __DIR__ . '/../view/funcoes/trocarsenha/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'Funcoes' => __DIR__ . '/../view',
        ),
    ),
);
