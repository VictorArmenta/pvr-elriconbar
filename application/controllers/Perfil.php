<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perfil extends MY_Controller
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
        $this->view_data['Users'] = User::all();
        $this->view_data['Timezones'] = $this->tz_list();
        $this->content_view = 'setting/setting';
    }

	public function editUserPass($id = FALSE) /*JAR*/
    {
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        if ($_POST) {
			if ($_POST['PasswordRepeat'] == $_POST['password'] ){
			
				$config['encrypt_name'] = TRUE;
				$user = User::find($id);
            
				$_POST['created_at'] = $date;
				unset($_POST['PasswordRepeat']);
				if ($_POST['password'] === '')
					unset($_POST['password']);
				$user->update_attributes($_POST);
				redirect("", "location");
			}
			else {
				echo '<script type="text/javascript">alert("Las contraseñas no coinsiden"); </script>';
				 $this->view_data['user'] = User::find($id);
                 $this->content_view = 'setting/modifyUserPass';
				}
			
        } else {
          $this->view_data['user'] = User::find($id);
          $this->content_view = 'setting/modifyUserPass';
        }
    }
}
