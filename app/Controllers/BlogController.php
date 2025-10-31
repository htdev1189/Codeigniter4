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

    // search
    public function search()
    {
        // nhan keyword
        $keyword = $this->request->getGet('q');
        if (empty($keyword)) {
            return redirect()->route('blog.home')->with('error', 'Vui lòng nhập từ khóa tìm kiếm');
        }


        $page = $this->request->getGet('page_search');
        // Nếu có truyền page_search và nó không hợp lệ thì redirect
        if ($page !== null && (!is_numeric($page) || $page < 1)) {
            return redirect()->to(base_url("search?q=" . urlencode($keyword)));
        }

        // Nếu không có page_tags, gán mặc định là 1
        $page = $page ? (int)$page : 1;

        $result = $this->postService->search($keyword);
        $totalPages = $result['pager']->getPageCount('search');
        if ($page > $totalPages && $totalPages > 0) {
            return redirect()->to(base_url("search?q=" . urlencode($keyword) . "&page_search={$totalPages}"));
        }

        $data = [
            "search" => $keyword,
            "pageTitle" => "Search: $keyword",
            "posts" => $result["posts"],
            "pager" => $result["pager"],
        ];
        return view('frontend/pages/search', $data);
    }

    // contact
    public function contact()
    {
        $data = [
            "pageTitle" => "Contact Us"
        ];
        return view("frontend/pages/contact", $data);
    }

    // submit contact
    public function sendContact()
    {
        // thiết lập rules
        $rules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vui lòng nhập họ tên',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Vui lòng nhập Email',
                    'valid_email' => "Vui lòng nhập đúng định dạng email"
                ]
            ],
            'subject' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vui lòng nhập Subject',
                ]
            ],
            'message' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vui lòng nhập Message',
                ]
            ]
        ];

        $data = $this->request->getPost(array_keys($rules));

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        // Dữ liệu sau khi lấy thành công
        $data = $this->request->getPost();
        // thực hiện gửi mail

        // render email body
        $view = service('renderer');
        $email_body = $view->setVar('data', $data)->render('frontend/email_temp/contact');

        // mail config
        $mailConfig = [
            'mail_from_email' => $data['email'],
            'mail_from_name' => $data['name'],
            'mail_to_email' => get_setting()->blog_email,
            'mail_to_name' => get_setting()->blog_title,
            'mail_subject' => $data['subject'],
            'mail_body' => $email_body
        ];
        if (sendEmail($mailConfig)) {
            return redirect()->route('blog.contact')->with('success', 'Thông tin của bạn đã được gửi tới chúng tôi');
        } else {
            return redirect()->route('blog.contact')->with('error', 'Đã có lỗi xảy ra vui lòng kiểm tra lại');
        }
       
    }
}
