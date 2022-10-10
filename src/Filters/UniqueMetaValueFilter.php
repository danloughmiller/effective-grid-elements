<?php
namespace EffectiveGrid\Filters;

class UniqueMetaValueFilter extends DefinedOptionsDropdownFilter
{
    public string $post_type;
    public string $meta_key;

    function __construct(string $field_name, string $label, string $placeholder='', string $meta_key, string $post_type, string|array $current_value='', $classes=array())
    {
        $this->meta_key = $meta_key;
        parent::__construct($field_name, $label, $placeholder, array(), '', $classes);

        $this->post_type = $post_type;
        $this->meta_key = $meta_key;

        $this->populateUniqueMetaValues($meta_key, $post_type);
    }

    protected function getPostIdsForSelectedValue(string $value): array
    {
        $args = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => $this->meta_key,
                    'value' => $value,
                    'compare' => '='
                )
            )
        );

        $query = new \WP_Query($args);

        $ids = array();

        foreach ($query->posts as $post)
            $ids[] = $post->ID;

        return $ids;
    }

    function populateUniqueMetaValues(string $meta_key, string $post_type = '')
    {
        global $wpdb;

        $query = "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s";

        if (!empty($post_type))
            $query .= " AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type = %s)";

        $query .= " ORDER BY meta_value ASC";

        $values = $wpdb->get_col($wpdb->prepare($query, $meta_key, $post_type));

        $values = array_filter($values, function ($value) {
            return !is_null($value);
        });

        $this->options = array_combine($values, $values);

        foreach ($values as $value)
            $this->select->addOption($value, $value);
    }
    
}