<?php
namespace EffectiveGrid\Grids;

use EffectiveGrid\Filters\Filters;
use EffectiveHtmlElements\HTMLElement;

class Grid extends HTMLElement
{
	public string $grid_id = '';
	public ?Filters $filters = null;
	
	public bool  $_renderFilters = true;
	
	public $elements = array();
	
	//Paging
	public bool $paged = true;
	public int $itemsPerPage = 50;
	public int $page = 1;
	
	
	public function __construct($grid_id, $classes=array(), $id='')
	{
		$classes[] = 'effective-grid';
		$this->grid_id = $grid_id;
		parent::__construct('div', $classes, $id??'effective-grid-'.$grid_id);

		$this->filters = new Filters();
	}
		
	public function xrender()
	{
		$ret = '<div id="effect-grid-'.$this->grid_id . '" class="' . implode(' ', $this->getClasses()) . '">';
		
		if ($this->_renderFilters && !empty($this->filters))
			$ret .= $this->filters->render();
		
		$elements = $this->getElements();
	
		$ret .= '<div class="effective-grid-elements-container">';
		if (!empty($elements)) {
			$ret .= 	'<ul class="effective-grid-elements">';
			$ret .= $this->renderElements($elements);
			$ret .= 	'</ul>';
		} else {
			$ret .= $this->renderEmptyResult();
		}
		$ret .= '</div>';

		if ($this->_renderPages && $this->paged && ($this->_renderSinglePagePagination || $this->getPageCount()>1)) {
            $ret .= $this->renderPagination();
        }
        
		$ret .= '</div>';
		
		return $ret;
	}

	function renderEmptyResult()
    {
        return '<div class="effective-grid-empty"><p>No results matched your search</p></div>';
    }
	
	function renderElements()
	{
		
		$ret = '';
		
		//Filter
		$elements = $this->getElements();
		
		if (is_array($elements) && count($elements)>0) {
			foreach ($elements as $element) {
				$ret .= $this->renderElement($element);
			}
		}
		
		return $ret;
	}
	
	function renderElement($el)
	{
        $ret = '<li id="' . $el->getId() . '" class="' . implode(" ",$el->getClasses()) . '">';
        $ret .= '<div class="effect-grid-element-content">';
        $ret .= $el->render();
        $ret .= '</div>';
		$ret .= '</li>';
		return $ret;
	}
	
	function renderPagination()
	{
		
		$ret = '';
		$ret .= '<div class="effective-grid-pagination">';
		$ret .= '	<ul>';
		
		
		$ret .= $this->renderPaginationElements();
		
		
		$ret .= '	</ul>';
		$ret .= '</div>';
		
		$ret .= '</div>';
		
		return $ret;
	}
	
	function renderPaginationElements()
	{
		$x = $this->page;
		$x = max($x-4,1);
		$y=min($x+8, $this->getPageCount());
				
		$ret = '';
		
        $ret .= $this->renderPaginationElement(1, '&laquo;', 'egrid-page-link-first');

		for ($i=$x;$i<=$y;$i++) {
            
			    $ret .= $this->renderPaginationElement($i);
		}
		$ret .= $this->renderPaginationElement($this->getPageCount(), '&raquo;', 'egrid-page-link-last');
		
		return $ret;
	}
	
	function renderPaginationElement($pindex, $label='', $class='')
	{
		$ret = '<li class="egrid-page-link-' . $pindex . ' ' . $class . ' ' . ($pindex==$this->page?'egrid-current-page ':'') . (abs($pindex-$this->page)<=1?'egrid-close-page':'') . '"><a href="' . $this->getPaginationLink($pindex) . '">' . (!empty($label)?$label:$pindex) . '</a></li>';
		return $ret;
	}
	
	function getPaginationLink($pindex) { 
		return '?egrid_page='.$pindex;
	}
	
	function getElements() {}
	function getElementCount() { }
	function getPageCount()	{ 
        return ceil($this->getElementCount() / $this->itemsPerPage);
    }
	function setPage($page) { $this->page = $page; }
	
}


