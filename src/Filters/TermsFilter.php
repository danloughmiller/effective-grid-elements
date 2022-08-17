<?php
namespace EffectiveGrid\Filters;

class TermsFilter extends DropdownFilter
{
	public string $taxonomy = '';
	
	public function __construct(string $taxonomy, string $label, $placeholder='', $current_value='', $classes=array())
	{
		parent::__construct($taxonomy, $label, $placeholder, array(), $current_value, $classes);
		
		if ($taxonomy) {
			$this->taxonomy = $taxonomy;

            $terms = $this->getTerms();
		
			if ($terms) {
				foreach ($terms as $t) {
					$this->select->addOption($t->name, $t->slug);
				}
			}
		}
    }

	/**
	 * Retrieves the terms to be used by this filter
	 *
	 * @return WP_Term[]
	 */
	function getTerms() : array
	{
		$res = get_terms(array('taxonomy'=>$this->taxonomy, 'parent'=>0));	
		
		if (!is_array($res))
			$res = array();

		return $res;
	}

	function applyFilter(array $args): array
	{
		if (empty($this->current_value))
			return $args;

		if (!empty($args['tax_query'])) {
			$tax_query = $args['tax_query'];
		} else {
			$tax_query = array();
		}

		$tax_query[] = array(
			'taxonomy' => $this->taxonomy,
			'field' => 'slug',
			'terms' => $this->current_value,
			'operator' => 'IN'
		);

		$args['tax_query'] = $tax_query;

		return $args;
	}

}