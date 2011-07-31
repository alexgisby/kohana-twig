<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twig template controller
 *
 * @package    Kohana-Twig
 * @author     John Heathco <jheathco@gmail.com>, Alex Gisby <alex@solution10.com>
 */
abstract class Kohana_Controller_Template_Twig extends Controller
{
	/**
	 * @var Twig_Environment
	 */
	public $environment = 'default';

	/**
	 * @var boolean  Auto-render template after controller method returns
	 */
	public $auto_render = TRUE;

	/**
	 * @var Twig
	 */
	public $template;

	/**
	 * Setup view
	 *
	 * @return void
	 */
	public function before()
	{
		if (empty($this->template))
		{
			// Work out the directory, controller and action based on Kohana version:
			if(version_compare(Kohana::VERSION, '3.1.0', '>='))
			{
				// 3.2
				$directory	= $this->request->directory();
				$controller = $this->request->controller();
				$action		= $this->request->action();
			}
			else
			{
				$directory 	= $this->request->directory;
				$controller	= $this->request->controller;
				$action		= $this->request->action;
			}
			
			// Generate a template name if one wasn't set.
			$this->template = str_replace('_', DIRECTORY_SEPARATOR, $controller).DIRECTORY_SEPARATOR.$action;

			if ( ! empty($directory))
			{
				$this->template = $directory.DIRECTORY_SEPARATOR.$this->template;
			}
		}

		if ($this->auto_render)
		{
			// Load the twig template.
			$this->template = Twig::factory($this->template, $this->environment);

			// Return the twig environment
			$this->environment = $this->template->environment();
		}

		return parent::before();
	}

	/**
	 * Renders the template if necessary
	 *
	 * @return void
	 */
	public function after()
	{
		if ($this->auto_render)
		{
			// Auto-render the template
			if(version_compare(kohana::VERSION, '3.1.0', '>='))
			{
				$this->response->body($this->template);
			}
			else
			{
				$this->request->response = $this->template;
			}
		}

		return parent::after();
	}

} // End Controller_Twig
