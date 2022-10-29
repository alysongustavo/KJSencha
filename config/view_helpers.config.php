<?php

namespace KJSencha;

use KJSencha\View\Helper\ExtJS;
use Laminas\ServiceManager\AbstractPluginManager;

return array(
    'factories' => array(
        'extJs' => function($pluginManager) {
            $config = $pluginManager->getServiceLocator()->get('config');

            return new ExtJS(
                $config['kjsencha']['library_path'],
                $pluginManager->get('headLink'),
                $pluginManager->get('headScript')
            );
        },
    )
);