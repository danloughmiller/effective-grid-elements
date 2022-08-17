<?php
namespace EffectiveGrid\Filters;

use EffectiveHtmlElements\Html\Div;

class FilterActions extends Div
{
    function __construct($classes=array(), $id='')
    {
        $classes[] = 'effective-grid-filter-actions';
        parent::__construct(false, $classes, $id);        
    }
}