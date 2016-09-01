<?php
namespace Wpm\Components;

class Taxonomy
{
    protected $data;
    
    
    /**
     * Store data as a property and get on with it
     * 
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    
    
    /**
     * Create a taxonomy.
     * 
     * @param array $args
     */
    public function create($args=[])
    {
        // set post type to last post_type used if empty
        $singular = (empty($args['name']) ? false : $args['name']);
        $plural = issetOrDefault($args, 'pluralName', $singular . 's');
        $slug = issetOrDefault($args, 'slug', strtolower($singular));
    
        if(!$slug or !$singular) return;
    
        // unset these arguments before registering
        unset($args['singular']);
        unset($args['plural']);
        unset($args['slug']);
        
        $labels = array(
            'name' => __( $plural ),
            'singular_name' => __( $singular ),
            'search_items' => __( 'Search ' . $plural ),
            'popular_items' => __( 'Popular ' . $plural ),
            'all_items' => __( 'All ' . $plural ),
            'parent_item' => __( 'Parent ' . $singular ),
            'parent_item_colon' => __( 'Parent ' . $singular . ':' ),
            'edit_item' => __( 'Edit ' . $singular ),
            'update_item' => __( 'Update ' . $singular ),
            'add_new_item' => __( 'Add New ' . $singular ),
            'new_item_name' => __( 'New ' . $singular . ' Name' ),
        );
        $buildArgs = array_merge(array(
            'label' => $plural,
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'hierarchical' => true,
            'update_count_callback' => null,
            'rewrite' => true,
        ), $args);
    
        register_taxonomy($slug, $buildArgs['postType'], $buildArgs); 
    }
    
}