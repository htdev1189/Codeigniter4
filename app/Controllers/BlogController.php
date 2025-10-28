<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Post;
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
        // $post = new Post();

        if (!$catData) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }


        $page = $this->request->getGet('page_cats');

        // Nếu có truyền page_cats và nó không hợp lệ thì redirect
        if ($page !== null && (!is_numeric($page) || $page < 1)) {
            return redirect()->to(base_url("category/{$slug}?page_cats=1"));
        }

        // Nếu không có page_tags, gán mặc định là 1
        $page = $page ? (int)$page : 1;


        // Lấy bài viết theo danh mục đệ quy
        $result = get_all_posts_by_category($catData['id']);
        // Giới hạn trang nếu vượt quá tổng số trang
        $totalPages = $result['pager']->getPageCount('cats'); // chú ý thêm tên được khai báo trong controller vào
        if ($page > $totalPages && $totalPages > 0) {
            return redirect()->to(base_url("category/{$slug}?page_cats={$totalPages}"));
        }

        $data = [
            "pageTitle" => $catData['name'],
            "breadcrumbs" => get_all_parent_categories($catData['id']),
            "category" => $catData,
            // Vì paginate() gắn dữ liệu phân trang vào bên trong đối tượng model $postModel, nhưng bạn lại không lấy ra nó.
            // Còn \Config\Services::pager() chỉ là một instance rỗng, không biết tổng số dòng, tổng số trang,...
            // "pager" => \Config\Services::pager(), // không đúng mà nên trả 'pager' => $postModel->pager từ helper
            "posts" => $result['posts'],
            "pager" => $result['pager'],
        ];

        return view('frontend/pages/category', $data);
    }

    // tags
    public function readTag($title)
    {


        $page = $this->request->getGet('page_tags');
        // if ($page < 1) {
        //     return redirect()->to(base_url("tag/" . urlencode($title) ."?page_tags=1"));
        // }
        // $page = (int)$page;

        // Nếu có truyền page_tags và nó không hợp lệ thì redirect
        if ($page !== null && (!is_numeric($page) || $page < 1)) {
            return redirect()->to(base_url("tag/" . urlencode($title) . "?page_tags=1"));
        }

        // Nếu không có page_tags, gán mặc định là 1
        $page = $page ? (int)$page : 1;



        $result = $this->postService->getByTag($title); // return current page and paginate model Post->pager :)
        $totalPages = $result['pager']->getPageCount('tags'); // chú ý thêm tên được khai báo trong controller vào
        if ($page > $totalPages && $totalPages > 0) {
            return redirect()->to(base_url("tag/" . urlencode($title) . "?page_tags={$totalPages}"));
        }

        $data = [
            "pageTitle" => "tags: $title",
            "posts" => $result["posts"],
            "pager" => $result["pager"],
        ];
        return view('frontend/pages/tag', $data);
    }

    public function page404()
    {
        return view('frontend/pages/404');
    }
}
