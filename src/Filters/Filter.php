<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\Html\Label;
use EffectiveHtmlElements\HTMLElement;

abstract class Filter extends HTMLElement
{
	public Label $label;
		
	public function __construct(string $label, $classes=array(), $id='')
	{
		$classes[] = 'effective-grid-filter';
		parent::__construct(false, $classes, $id);

		$this->addChild($this->label = new Label($label));
	}
	
	/**
	 * Applies this filter to the query
	 *
	 * @param array $args
	 * @return array
	 */
	public function applyFilter(array $args)
	{
		return $args;
	}
	
}