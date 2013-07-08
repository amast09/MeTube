<?php
class Account extends CI_Controller {

	// site_url()/index.php
	function index() {
		// if the user isn't logged in, start at the sign up page
		if(!$this->session->userdata('logged_in'))
			$this->sign_up();
		else {
			// otherwise, edit account info
			$data['content'] = 'edit_account';
			$data['fname'] = $this->session->userdata('firstName');
			$data['lname'] = $this->session->userdata('lastName');
			$this->load->view('includes/template', $data);
		}
	}

	// verify login information
	function validate_credentials() {
		// load needed models and libraries
		$this->load->model('account_model');
		$this->load->library('form_validation');

		// set the form validation requirements
		$this->form_validation->set_rules('userName', 'Username', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|alpha_dash');

		if($this->form_validation->run()) {
			$userName = $this->input->post('userName');
			$password = md5($this->input->post('password'));

			if($this->account_model->validate($userName, $password)) {
				// successful login
				$this->session->set_userdata($this->account_model->set_session($userName));
			} else {
				// failed login
				redirect($this->input->post('url'));
			}
			
			// redirect the user to where they previously were
			redirect($this->input->post('url'));
		}
	}

	// Log out
	function logout() {
		$this->session->sess_destroy(); 
		redirect('dashboard');
	}

	// load the sign up form
	function sign_up() {
		if($this->session->userdata("logged_in")) redirect('dashboard');
		$data['content'] = 'signup_form';
		$this->load->view('includes/template', $data);
	}

	// edit user account info
	function edit_account() {
		// load the needed models and libraries
		$this->load->model('account_model');
		$this->load->library('form_validation');
		
		// set form validation rules
		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required|min_length[2]|max_lenth[20]|alpha');
		$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|min_length[2]|max_lenth[20]|alpha');
		$this->form_validation->set_rules('newPassword', 'New Password', 'trim|required|min_length[6]|max_length[32]|alpha_dash');
		$this->form_validation->set_rules('newPassword2', 'New Password 2', 'trim|required|matches[newPassword]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]|alpha_dash');

		if(!$this->form_validation->run()) {
			// the form wasn't properly filled in
			// redirect to the account index page
			$data['content']='failure.php';
			$data['errors']=validation_errors();
			$this->load->view('includes/template',$data);
		} else{
			// everything seems good, update the account
			$userName = $this->session->userdata('userName');	
			$firstName = $this->input->post('firstName');
			$lastName = $this->input->post('lastName');
			$password = md5($this->input->post('newPassword'));
			$currentPassword = md5($this->input->post('password'));

			// if the user didn't input their current password correctly, reload the index
			if(!$this->account_model->validate($userName, $currentPassword)) redirect('account');

			if($this->account_model->update_account($userName, $password, $firstName, $lastName)) {
				// the account was successfully created.
				// send the user to their dashboard
				$this->session->set_userdata($this->account_model->set_session($userName));
				redirect('dashboard');
			} else {
				// the account wasn't successfully created.
				// redirect the user to the account index page
				$this->index();
			}
		}
	}

	// create a new user account
	function create_account() {
		// load the needed models and libraries
		$this->load->model('account_model');
		$this->load->library('form_validation');
		
		// set form validation rules
		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required|min_length[4]|max_lenth[20]|alpha');
		$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|min_length[2]|max_lenth[20]|alpha');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|max_length[50]|is_unique[Account.email]');
		$this->form_validation->set_rules('userName', 'User Name', 'trim|required|min_length[4]|max_lenth[20]|is_unique[Account.userName]|alpha_dash');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]|alpha_dash');
		$this->form_validation->set_rules('password2', 'Password 2', 'trim|required|matches[password]|alpha_dash');
	      
		if(!$this->form_validation->run()) {
			// the form wasn't properly filled in
			// redirect to the sign up page
			$data['content'] = 'failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template',$data);
			//echo validation_errors();
		} else{
			// the fields were filled in correctly
			// set the fields for creating the account
			$userName = $this->input->post('userName');
			$email = $this->input->post('email');
			$password = md5($this->input->post('password'));
			$firstName = $this->input->post('firstName');
			$lastName = $this->input->post('lastName');

			if($this->account_model->create_account($userName, $email, $password, $firstName, $lastName)) {
				// the account was successfully created.
				// send the user to their dashboard
				$data['content'] = 'successful_creation';
				$this->load->view('includes/template', $data);
			} else {
				// the account wasn't successfully created.
				// redirect the user to the sign up page
				echo "DB Error";
			}
		}
	}

}

?>
