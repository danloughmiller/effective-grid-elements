<?php
namespace EffectiveGrid\Grids;

use EffectiveGrid\Elements\Element;
use EffectiveGrid\Filters\FilterContainer;
use EffectiveHtmlElements\HTMLElement;
use EffectiveHtmlElements\Theme\Layout\GridElement;

abstract class Grid extends HTMLElement
{
	public string $grid_id = '';
	public ?FilterContainer $filter_container = null;
	public ?GridElement $elements = null;
	
	/** @var int $itemsPerPage The number of items to show per page, or -1 for no paging */
	public int $itemsPerPage = -1;
	public int $current_page = 1;
	
	public function __construct($grid_id, $classes=array(), $id='')
	{
		$classes[] = 'effective-grid';
		$this->grid_id = $grid_id;
		parent::__construct('div', $classes, $id??'effective-grid-'.$grid_id);

		$this->filter_container = new FilterContainer();
		$this->addChild($this->elements = new GridElement());
	}

	function render() : string
	{
		foreach ($this->getCurrentElements() as $element) {
			$this->elements->addChild($element);
		}

		return parent::render();
	}

	function getRenderableChildren() : array
	{
		$children = parent::getRenderableChildren();

		// Add the filter container to the beginning of the children
		// array if it has any children.
		if (!empty($this->filter_container->filters))
			array_unshift($children, $this->filter_container);

		return $children;
	}

	/**
	 * Retrieves the elements to display, accounting for applied filters and paging
	 * 
	 * @return Element[]
	 */
	abstract function getCurrentElements() : array;

	/**
	 * Returns the total number of elements in the entire grid account for filters
	 */
	abstract function getTotalElementCount() : int;

	/**
	 * Returns the number of pages total
	 */
	function getPageCount() : int { 
        return ceil($this->getTotalElementCount() / $this->itemsPerPage);
    }

	/**
	 * Sets the current 1-based page index
	 *
	 * @param int $page
	 */
	function setPage(int $page) : void { 
		$this->page = min($this->getPageCount(), max(1, $page));
	}

	/**
	 * Create an element from supplied data
	 */
	abstract function createElement($data) : HTMLElement;

	/**
	 * Constructs and returns the query to use to retrieve the elements
	 */
	protected function constructQuery() : array {
		return array();
	}

	/**
	 * Applies filters to the query
	 *
	 * @param array $query
	 */
	protected function applyFilters($query) : array {
		if(!empty($this->filter_container->filters)) {
			foreach ($this->filter_container->filters as $filter)
			{
				$query = $filter->applyFilter($query);
			}
		}

		return $query;
	}

}


