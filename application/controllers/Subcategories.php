<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcategories extends MY_Controller
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
        $this->view_data['subcategories'] = Subcategory::all();
        $this->content_view = 'subcategory/view';
    }

    public function add()
    {
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        $_POST['created_at'] = $date;
        $user = Subcategory::create($_POST);
        redirect("subcategories", "refresh");
        $categories->update_attributes($data);

    }

    public function edit($id = FALSE)
    {
        if ($_POST) {
            $subcategory = Subcategory::find($id);
            $subcategory->update_attributes($_POST);
            redirect("subcategories", "refresh");
        } else {
            $this->view_data['subcategory'] = Subcategory::find($id);
            $this->content_view = 'subcategory/edit';
        }
    }

    public function delete($id)
    {
        $subcategory = Subcategory::find($id);
        $subcategory->delete();
        redirect("subcategories", "refresh");
    }
}
