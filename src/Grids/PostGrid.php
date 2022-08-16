<?php
namespace EffectiveGrid\Grids;

use EffectiveHtmlElements\Element;
use EffectiveHtmlElements\HTMLElement;
use EffectiveHtmlElements\Theme\Elements\PostCards\WPPostCard;

class PostGrid extends Grid
{
	public string $post_type = 'post';
		
	public function __construct(string $grid_id, string $post_type='post', $classes=array(), string $id='')
	{
		$classes[] = 'effective-grid-post-grid';
		parent::__construct($grid_id, $classes, $id);
		
		$this->post_type = $post_type;
	}

	function createElement($data) : WPPostCard
	{
		return new WPPostCard($data);
	}

	function getCurrentElements(): array
	{
		$posts = get_posts($this->applyFilters($this->constructQuery()));
					
		$elements = array();
		foreach ($posts as $r) {
			$elements[] = $this->createElement($r);
		}

		return $elements;
	}

	function getTotalElementCount() : int {
        $query = $this->constructQuery();
        $query['posts_per_page']=-1;
        $q = get_posts($query);
		return count($q);
	}

	
	protected function constructQuery() : array
	{		
		$args = array(
			'post_type'=>$this->post_type,
			'posts_per_page'=>$this->itemsPerPage,
			'paged'=>$this->page,
            'orderby'=>'title',
            'order'=>'ASC'
        );
        
        if (is_array($this->additional_query_args))
            $args = array_merge($args, $this->additional_query_args);
        
		return $args;
	}
	
	
	function getPaginationLink($pindex) { 
		$link = '?egrid_page='.$pindex;
		
		if (!empty($this->filters->filters) && count($this->filters->filters)>0) {			
			foreach ($this->filters->filters as $filter) {
				if (!empty($filter->selected)) {
					$link .= '&egrid_filter[' . $filter->taxonomy . ']=' . $filter->selected;
				}
			}
		}
		
		return $link;
	}
	
}







