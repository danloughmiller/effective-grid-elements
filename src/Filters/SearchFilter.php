<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\Html\Forms\TextInput;

class SearchFilter extends Filter
{
	public TextInput $search_field;
	
	public function __construct(string $field_name, string $label='', string $placeholder='Search', string $current_value='', array $classes=array())
	{
		parent::__construct($field_name, $label, $current_value, $classes);

		$this->addChild($this->search_field = new TextInput($this->field_name, $placeholder, $current_value));
		$this->search_field->placeholder = $placeholder;
	}

	function applyFilter(array $args): array
	{
		if (!empty($this->current_value))
			$args['s'] = $this->current_value;

		return $args;
	}
	
}