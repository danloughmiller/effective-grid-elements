<?php
namespace EffectiveGrid\Filters;

use EffectiveGrid\Filters\DropdownFilter;

abstract class DefinedOptionsDropdownFilter extends DropdownFilter
{
	
	public function __construct(string $field_name, string $label, $placeholder='', $options=array(), $current_value='', array $classes=array(), string $id='')
	{
		parent::__construct($field_name, $label, $placeholder, $options, $current_value, $classes, $id);
    }
	
	function applyFilter(array $args): array
	{
		if (!empty($this->selected)) {
			$definedSet = $this->getPostIdsForSelectedValue($this->current_value);
			
			if (empty($args['post__in']))
				$args['post__in'] = array();

			$args['post__in'] = array_merge($args['post__in'], $definedSet);            
        }

		return $args;
	}
    
	/**
	 * Returns an array of integers of post IDs that are valid for the given value
	 *
	 * @param string $value
	 * @return integer[]
	 */
    abstract protected function getPostIdsForSelectedValue(string $value) : array;

}