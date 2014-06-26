<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge;

use Twig_Environment;
use Twig_LoaderInterface;
use Illuminate\Foundation\Application;
use InvalidArgumentException;
use Twig_Error;

/**
 * Bridge functions between Laravel & Twig
 */
class Bridge extends Twig_Environment
{
    /**
     * @var string TwigBridge version
     */
    const BRIDGE_VERSION = '0.6.0';

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function __construct(Twig_LoaderInterface $loader = null, $options = [], Application $app = null)
    {
        parent::__construct($loader, $options);

        $this->app = $app;
    }

    /**
     * Get the Laravel app.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Set the Laravel app.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Lint (check) the syntax of a file on the view paths.
     *
     * @param string $file File to check. Supports dot-syntax.
     *
     * @return bool Whether the file passed or not.
     */
    public function lint($file)
    {
        $template = $this->app['twig.loader.viewfinder']->getSource($file);

        if (!$template) {
            throw new InvalidArgumentException('Unable to find file: '.$file);
        }

        try {
            $this->parse($this->tokenize($template, $file));
        } catch (Twig_Error $e) {
            return false;
        }

        return true;
    }
}
