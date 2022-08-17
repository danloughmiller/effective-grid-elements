<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\HTMLElement;
use EffectiveHtmlElements\Theme\Elements\AnchorButton;
use EffectiveHtmlElements\Theme\Elements\SubmitButton;

class FilterContainer extends HTMLElement
{
	/** @var Filter[] $filters */
    public $filters = array();

	public FilterActions $actions;

	public AnchorButton $reset_button;
	public SubmitButton $update_button;

    public $labels = array(
        'reset_filters' => 'Reset Filters',
        'update_filters' => 'Update Filters',
    );
	
	public function __construct($classes=array(), string $id='')
	{
		$classes[] = 'effective-grid-filter-container';
		parent::__construct('form', $classes, $id);
		
		$this->addChild($this->actions = new FilterActions());

		$this->actions->addChild($this->update_button = new SubmitButton($this->labels['update_filters']));
		$this->actions->addChild($this->reset_button = new AnchorButton($this->labels['reset_filters'], parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)));
	}
	
	public function getFilters() : array
	{
		return $this->filters;
	}
	
	public function addFilter($filter) : void
	{
		$this->filters[] = $filter;
	}

	function getRenderableChildren()
	{
		$children = parent::getRenderableChildren();
		
		if (!empty($this->filters))
			 $children = array_merge($this->filters, $children);
		
		return $children;

	}

}


