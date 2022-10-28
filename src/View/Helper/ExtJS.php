<?php

namespace KJSencha\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;

/**
 * Ext JS view helper
 */
class ExtJS extends AbstractHelper
{
    /**
     * Path which points to the library
     *
     * @var string
     */
    protected $libraryPath;

    /**
     * @var HeadLink
     */
    protected $headLink;

    /**
     * @var HeadScript
     */
    protected $headScript;

    protected $options = array(
        'development'   => true,
        'theme'         => 'default',
        'extCfg'        => array(),
        'libraryPath'   => '',
    );

    /**
     * @param string $headLink
     * @param HeadLink $headLink
     * @param HeadScript $headScript
     */
    public function __construct($libraryPath, HeadLink $headLink, HeadScript $headScript)
    {
        $this->options['libraryPath'] = rtrim((string) $libraryPath, '/');
        $this->headLink = $headLink;
        $this->headScript = $headScript;
    }

    /**
     * Loading the library in a view
     *
     * @param array $options
     */
    public function loadLibrary()
    {
        $libVersion = $this->options['development'] ? 'ext-all-dev.js' : 'ext-all.js';
        $this->headLink->appendStylesheet($this->options['libraryPath'] . '/resources/css/ext-all.css');
        $this->headScript->prependFile($this->options['libraryPath'] . '/' . $libVersion);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = ArrayUtils::merge($this->options, $options);
    }
}