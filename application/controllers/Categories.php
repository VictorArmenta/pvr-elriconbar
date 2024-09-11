<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->view_data['categories'] = Category::all();
        $this->content_view = 'category/view';
    }

    public function add()
    {
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
       // $_POST['created_at'] = $date;
		$data = array(
                   "created_at" => $date,
                   "name" => TRIM($_POST['name'])
		 );		   
        $user = Category::create($data);
        redirect("categories", "refresh");
        $categories->update_attributes($data);

    }

    public function edit($id = FALSE)
    {
        if ($_POST) {
            $category = Category::find($id);
			$data = array(
                   "name" => TRIM($_POST['name'])
			);
            $category->update_attributes($data);
            redirect("categories", "refresh");
        } else {
            $this->view_data['category'] = Category::find($id);
            $this->content_view = 'category/edit';
        }
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        redirect("categories", "refresh");
    }
}
