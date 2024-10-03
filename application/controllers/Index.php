<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('ratelimit');
//	$this->load->library('Auth');
//	$this->auth = (new Auth())->initialize();
    }
    public function index($error = '')
    {
        $data['error'] = '';
        $data['member_username'] = '';
        $data['member_password'] = '';
        $data['is_sign'] = 0;
// Add By shital for language module : 19:01:2024
         $query = "SELECT ml_short,ml_name,ml_id,ml_actual_text FROM ai_multi_language WHERE status=2";
         $result = $this->db->query($query);
         $data['multi_lang'] = $result->result_array();
         // End By shital for language module : 19:01:2024
        $this->load->view('auth/signin', $data);
    }
    public function login($error = '')
    {
// Add By shital for language module : 19:01:2024
         $query = "SELECT ml_short,ml_name,ml_id,ml_actual_text FROM ai_multi_language WHERE status=2";
         $result = $this->db->query($query);
         $data['multi_lang'] = $result->result_array();
         // End By shital for language module : 19:01:2024

        $mw_session = $this->session->userdata('awarathon_session');
        if (isset($mw_session)) {
            // redirect('dashboard');
            redirect($mw_session['home']);
        } else {
            //KRISHNA --- VAPT - ENABLED MAX 3 ATTEMPT FOR LOGIN WITHIN 5 MINUTES
            // if($this->session->userdata('login_attempt_session')){
            //     $data['error']='HTTP 429';

            //     $isBlock = limitRequests($this->input->ip_address(),3,300);
            //     if($isBlock !== 'HTTP 429'){
            //         $this->session->unset_userdata('login_attempt_session');
            //         $data['error']='';
            //     }
            // }else{
            //     $data['error']='';
            // }

            $data['error'] = '';
            $member_username = '';
            $member_password = '';
            if (isset($_COOKIE["MW_token"])) {
                $login_secure = explode("///", $_COOKIE["MW_token"]);
                if (count((array) $login_secure) > 0) {
                    $member_username = base64_decode($login_secure[0]);
                    $member_password = base64_decode($login_secure[1]);
                }
            }
            $data['member_username'] = $member_username;
            $data['member_password'] = $member_password;
            $data['is_sign'] = 1;
            // $data['path'] = $this->config->item('base_url');
            // $this->load->view('login',$data);
            $this->load->view('auth/signin', $data);
        }

    }
    public function reset($user_id = '')
    {
// Add By shital for language module : 19:01:2024
         $query = "SELECT ml_short,ml_name,ml_id,ml_actual_text FROM ai_multi_language WHERE status=2";
         $result = $this->db->query($query);
         $data['multi_lang'] = $result->result_array();
         // End By shital for language module : 19:01:2024
         
        $error = '';
        $success = 1;
        $data['user_id'] = base64_decode($user_id);
        $this->load->model('common_model');
        $Rowset = $this->common_model->get_value('company_users', 'reset_link_expire_at', "userid='" . base64_decode($user_id) . "'");
        if (count((array) $Rowset) > 0) {
            $expire_date = $Rowset->reset_link_expire_at;
            $today_date = date("Y-m-d H:i:s");
            if ($today_date > $expire_date) {
                $error = "Reset password link is expired, Please try again.";
                $success = 0;
            }
        } else {
            $error = "User not Exist.";
            $success = 0;
        }
        if ($success) {
            $data['is_sign'] = 2;
        }else{
            $data['is_sign'] = 1;
        }
        $data['error'] = $error;
        $data['member_username'] = '';
        $data['member_password'] = '';
        // $data['path'] = $this->config->item('base_url');
        $this->load->view('auth/signin', $data);
    }

    public function auth() {
	header(sprintf('Location: %s', $this->auth->login()));
    }

    public function callback(){
	$hasAuthenticated = isset($_GET['state']) && isset($_GET['code']);
	$hasAuthenticationFailure = isset($_GET['error']);

	if ($hasAuthenticated) {
    try {
      $this->auth->exchange();
    } catch (\Throwable $th) {
      printf('Unable to complete authentication: %s', $th->getMessage());
      exit;
    }
	}

	if ($hasAuthenticationFailure) {
    printf('Authentication failure: %s', htmlspecialchars(strip_tags(filter_input(INPUT_GET, 'error'))));
    exit;
	}

	$this->load->model('login_model');

	$token = $this->login_model->getToken(10);
	$asset_url = $this->config->item('assets_url');

	$session = $this->auth->getCredentials();
	$this->db->where('email', $session->user['email']);
	$query = $this->db->get('users');
	$row = $query->row();
	if (count((array)$row) <= 0) {
		die("The user does not exist!");
	}

	$username = $row->username;

	$data = array(
                    'login_type' => 1,
                    'user_id' => '999999999',
                    'username' => $username,
                    'first_name' => 'Super',
                    'last_name' => 'Administrator',
                    'avatar' => $asset_url . "assets/uploads/avatar/no-avatar.jpg",
                    'role' => 1,
                    'user_type' => 1,
                    'company_id' => "",
                    'company_name' => "",
                    'validated' => true,
                    'superaccess' => true,
                    'user_token' => $token,
					'dashboard' => 'assessment_dashboard',
                    'division_id'=> 0,
                    'home' => 'home',
                );
	$this->session->set_userdata('awarathon_session', $data);

	redirect('home');
    }
}
