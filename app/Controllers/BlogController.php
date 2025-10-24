<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\PostService;
use CodeIgniter\HTTP\ResponseInterface;

class BlogController extends BaseController
{
    protected $helpers = ["url", "form", "CIMail", "CIFunction", "Frontend"];

    protected $categoryService;
    protected $postService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->postService = new PostService();
    }
    public function index()
    {
        $data = [
            "pageTitle" => "Home Page",
        ];
        return view('frontend/pages/home', $data);
    }

    public function readPost($slug)
    {
        $post = $this->postService->findBySlug($slug);

        // Nếu không tìm thấy bài viết → trả về 404
        if (!$post) {
            // Redirect đến trang 404 custom
            return redirect()->route('blog.page404');
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            "pageTitle" => $post['title'],
            "post"      => $post,
        ];

        return view('frontend/pages/article', $data);
    }


    public function readCat($slug)
    {
        $catData = $this->categoryService->getBySlug($slug);

        if (!$catData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // dd(get_all_posts_by_category($catData['id']));
        // $ids = get_all_parent_category_ids($catData['id']);

        $data = [
            "pageTitle" => $catData['name'],
            "breadcrumbs" => get_all_parent_categories($catData['id']),
            "category" => $catData,
            "posts" => get_all_posts_by_category($catData['id']), // Lấy bài viết theo danh mục
            "pager" => \Config\Services::pager(),
        ];

        return view('frontend/pages/category', $data);
    }

    public function page404()
    {
        return view('frontend/pages/404');
    }
}
