<?php

/**
 * Frontend helper
 */

// get category for header
if (function_exists('get_parent_categories') === false) {
    function get_parent_categories()
    {
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->asObject()
            ->where('deleted_at', null)
            ->where('parent_id', 0)
            ->orderBy('name', 'ASC')
            ->findAll();
        return $categories;
    }
}


// get catagory by parent id for sidebar
if (function_exists('get_categories') === false) {
    function get_categories($parent_id = 0)
    {
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->asObject()
            ->where('deleted_at', null)
            ->where('parent_id', $parent_id)
            ->orderBy('name', 'ASC')
            ->findAll();
        return $categories;
    }
}

// get post by slug
if (function_exists('get_post_by_slug') === false) {
    function get_post_by_slug($slug)
    {
        $postModel = new \App\Models\Post();
        $post = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('slug', $slug)
            ->first();
        return $post;
    }
}

// Lay het cac bai viet theo category -- de quy
if (!function_exists('get_all_posts_by_category')) {
    function get_all_posts_by_category($categoryId)
    {
        $postModel = new \App\Models\Post();
        $allCategoryIds = get_all_child_category_ids($categoryId);

        $posts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->whereIn('category_id', $allCategoryIds)
            ->paginate(2);
            // ->findAll();

        return $posts;
    }
}

