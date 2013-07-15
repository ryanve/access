<?php 
/*
Plugin Name: Access
Plugin URI: http://github.com/ryanve/access
Description: Control member access via a custom taxonomy that accepts roles, capabilities, or user IDs.
Version: 0.2.0
Author: Ryan Van Etten
Author URI: http://ryanve.com
License: MIT
*/

# codex.wordpress.org/Roles_and_Capabilities
# codex.wordpress.org/Plugin_API/Action_Reference

add_action('init', function() {
    $tax = 'access';
    $name = __('Access');
    $avoid = array('nav_menu_item', 'revision');
    $types = array_diff(get_post_types(), $avoid);
    register_taxonomy($tax, $types, apply_filters("@$tax:ui", array(
        'hierarchical' => 1
      , 'public' => current_user_can('delete_others_posts')
      , 'labels' => array(
            'all_items' => __('All')
          , 'popular_items' => __('Popular')
          , 'edit_item' => __('Edit')
          , 'view_item' => __('View')
          , 'update_item' => __('Update')
          , 'search_items' => __('Search')
          , 'add_new_item' => __('Add')
          , 'new_item_name' => __('Name')
          , 'add_or_remove_items' => __('Add or remove')
          , 'choose_from_most_used' => __('Most used')
          , 'not_found' => __('Not found')
          , 'separate_items_with_commas' => 'Use WP roles, capabilities, or user IDs.'
          , 'name' => $name
          , 'singular_name' => $name
        ))
    ));

    # Accommodate late post types.
    add_action('init', function() use ($tax, &$types, &$avoid) {
        if (taxonomy_exists($tax))
            foreach (array_diff(get_post_types(), $types, $avoid) as $type)
                register_taxonomy_for_object_type($tax, $type);
        unset($types, $avoid);
    }, 100);
    
    # Define the "test access" callback via filter to enable override.
    add_filter("@$tax:test", function($fn, $user = null) use ($tax) {
        # Callback result grants (truthy) or denies (falsey) access. 
        # Grant when not applicable, when no terms are applied, or when *any* term passes.
        $applicable = taxonomy_exists($tax) && !is_404() and $user = $user ?: wp_get_current_user();
        return $applicable ? function($post = null) use ($tax, $user) {
            $grant = 1;
            $terms = get_the_terms($post, $tax);
            if ($terms && is_array($terms) and $grant--)
                foreach (wp_list_pluck($terms, 'slug') as $slug)
                    if ($grant = is_numeric($slug) ? $user->id === $slug : $user->has_cap($slug))
                        break;
            return !!$grant;
        } : '__return_true';
    }, 0, 2);
    
    # Define the "contextual CSS" callback via filter to enable override.
    add_filter("@$tax:contextualize", function($fn) use ($tax) {
        return function($classes) use ($tax) {
            $classes = (array) ($classes ?: array());
            $grant = call_user_func(apply_filters("@$tax:test", null));
            $which = array('access-granted', 'access-denied');
            $classes[] = $grant ? array_shift($which) : array_pop($which);
            do_action("@$tax:" . ($grant ? 'granted' : 'denied') . '@' . current_filter());
            return array_diff(array_unique($classes), $which);
        };
    }, 0);
    
    is_admin() or add_action('wp', function() use ($tax) {
        if ($contextualize = apply_filters("@$tax:contextualize", null))
            foreach (array('post_class', 'body_class') as $hook)
                add_filter($hook, $contextualize);
    });
}, 1);

#end
