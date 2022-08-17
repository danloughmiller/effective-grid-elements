<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\Html\Label;
use EffectiveHtmlElements\HTMLElement;

abstract class Filter extends HTMLElement
{
	public Label $label;
	public string $field_name;
	public string|array $current_value;
		
	public function __construct(string $field_name='', string $label='', string|array $current_value,  $classes=array(), $id='')
	{
		$classes[] = 'effective-grid-filter';
		$classes[] = 'effective-grid-filter-' . strtolower(self::class);

		if (!empty($field_name))
			$classes[] = 'effective-grid-filter-' . $field_name;

		parent::__construct('div', $classes, $id);

		$this->field_name = $field_name;
		$this->addChild($this->label = new Label($label));

		$this->current_value = $current_value;;
	}
	
	/**
	 * Applies this filter to the query args
	 */
	public function applyFilter(array $args) : array
	{
		return $args;
	}

	/**
	 * Returns a string to use inside the input name e.g. egrid_filter[{field name}]
	 */
	function getFieldName() : string
	{
		return $this->field_name;
	}
	
}