<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\Html\Forms\Select;

abstract class DropdownFilter extends Filter
{
	public array $options = array();

	public bool $_renderSelect2 = true;

	public Select $select;
	
	public function __construct(string $field_name, string $label, string $placeholder='', $options=array(), string|array $current_value='', $classes=array())
	{
		parent::__construct($field_name, $label, $current_value, $classes);

		$this->options = $options;

		$this->addChild($this->select = new Select($this->field_name, $this->options, $current_value));

		if ($this->_renderSelect2)
		{
			$this->select->addClass('egrid-select2');
			$this->select->setDataAttr('minimum-results-for-search', 'Infinity');
		}
	}

}