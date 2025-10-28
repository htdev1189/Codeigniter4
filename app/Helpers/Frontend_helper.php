<?php

use App\Models\Post;

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
            ->paginate(3, 'cats'); // Thay vì để mặc định, đặt group name cố định, ví dụ "cats": $pager->links('cats', 'default_cat')
        // ->paginate(1); // mac dinh la default $pager->links('default', 'default_cat')
        // ->findAll();

        // return $posts;
        return [
            'posts' => $posts,
            'pager' => $postModel->pager
        ];
    }
}


// get all tags from post table
if (!function_exists('get_tags')) {
    function get_tags()
    {
        $postModel = new Post();

        $tagsArray = [];

        // find all post
        $posts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->where('tags !=', '')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($posts as $post) {
            $tags = explode(',', $post->tags); // tách tag theo dấu phẩy
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if ($tag !== '') {
                    $tagsArray[] = $tag;
                }
            }
        }

        return array_values(array_unique($tagsArray)); // loại bỏ giá trị trùng lặp và reset lai key
    }
}

// count post with tag
if (!function_exists('count_post_in_tag')) {
    function count_post_in_tag($tag)
    {
        $postModel = new Post();
        $posts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->like('tags', '%' . $tag . '%')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        return count($posts);
    }
}
