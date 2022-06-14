<?php

namespace KJSencha\Controller;

use Exception;
use KJSencha\Frontend as Ext;
use KJSencha\Service\ComponentManager;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Handles component creation requests which are grabbed from the global
 * ComponentManager
 */
class DataController extends AbstractActionController
{

    /**
     * @var ComponentManager
     */
    protected $componentManager;

    /**
     * @param ComponentManager $componentManager
     */
    public function __construct(ComponentManager $componentManager)
    {
        $this->componentManager = $componentManager;
    }

    /**
     * Component builder
     *
     * Fetches components from the ComponentManager and converts
     * them to a JSON format which can be read by a Ext.ComponentLoader
     *
     * When a component cannot be found or another exception occurs then a
     * new panel will be created which will contain the error message
     *
     * @return Response
     * @throws Exception
     */
    public function componentAction()
    {
        $response = $this->getResponse();
        $component = null;

        try {

            if ($componentName = $this->params()->fromPost('componentName')) {
                $component = $this->componentManager->get($componentName);
            } elseif ($componentConfig = $this->params()->fromPost('componentConfig')) {
                $component = $this->buildComponent(json_decode($componentConfig, true));
            }
        } catch (Exception $e) {
            // When something goes wrong create a new panel which holds the error message
            $attributes = array(
                'html'          => 'Exception: ' . $e->getMessage(),
                'bodyPadding'   => 5,
                'bodyStyle'     => 'color: #F00; text-align: center',
                'border'        => 0,
            );
            $component = new Ext\Panel($attributes);
        }

        $response->setContent($component->toJson());

        return $response;
    }

    /**
     * Build component
     *
     * @param array|string $component
     * @return array
     */
    protected function buildComponent($component)
    {
        $componentManager = $this->componentManager;

        $transformObj = function ($item) use ($componentManager) {
            if (ArrayUtils::isHashTable($item) && isset($item['cmp'])) {
                $itemObj = $componentManager->get($item['cmp']);
                $itemObj->setProperties($item);
                unset($itemObj['cmp'], $itemObj['extend']);
                $item = $itemObj;
            }
            return $item;
        };

        // Recursive mapping, convert to class later
        $map = function ($func, $arr) use (&$map, $transformObj) {
            $result = array();
            if (ArrayUtils::isHashTable($arr)) {
                foreach ($arr as $k => $v) {
                    $result[$k] = $map($func, $v);
                }
                $result = $transformObj($result);
            } elseif (ArrayUtils::isList($arr)) {
                foreach ($arr as $b) {
                    $result[] = $transformObj($b);
                }
            } elseif (is_string($arr)) {
                $result = $arr;
            }

            return $result;
        };

        return $map(function ($item) {
            return $item;
        }, $component);
    }
}
