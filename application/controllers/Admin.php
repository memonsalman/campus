<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
    
    class Admin extends EduAppGT
    {
        private $runningYear = '';
    
        function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->database();
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $this->output->set_header('Pragma: no-cache');    
            $this->runningYear = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        }
        
        //Index function for Admin controller.
        public function index()
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($this->session->userdata('admin_login') == 1)
            {
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
        }
        
        //Generate PDF after Admission form.
        function generate($student_id,$pw)
        {
            $this->crud->getPDF($student_id,$pw);
        }

        //Update SMTP Settings function.
        function smtp($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'update'){
                $this->crud->updateSMTP();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('success_update'));
                redirect(base_url() . 'admin/system_settings/', 'refresh');
            }
            $page_data['page_name']  = 'smtp';
            $page_data['page_title'] = getEduAppGTLang('smtp_settings');
            $this->load->view('backend/index', $page_data);
        }


        //Send Marks by SMS to Parents and Students.
        function send_marks($param1 = '', $param2 = '')
        {
            if($param1 == 'email')
            {
                if($this->input->post('receiver') == 'student')
                {
                    $this->mail->sendStudentMarks();
                }else{
                    $this->mail->sendParentsMarks();
                }
            }
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('marks_sent'));
            redirect(base_url() . 'admin/grados/', 'refresh');
        }
        
        //Download admission sheet function.
        function download_file($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['pw']  = $param2;
            $page_data['student_id']  = $param1;
            $page_data['page_name']  = 'download_file';
            $page_data['page_title'] = getEduAppGTLang('download_file');
            $this->load->view('backend/index', $page_data);
        }
        
        //Enter to live class function.
        function live($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['live_id']  = $param1;
            $page_data['page_name']  = 'live';
            $page_data['page_title'] = getEduAppGTLang('live');
            $this->load->view('backend/index', $page_data);
        }
        
        //Meet for Live Classes function.
        function meet($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->academic->createLiveClass();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/meet/'.$param2, 'refresh');
            }
            if($param1 == 'update')
            {
                $this->academic->updateLiveClass($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/meet/'.$param3, 'refresh');
            }
            if($param1 == 'delete')
            {
                $this->academic->deleteLiveClass($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/meet/'.$param3, 'refresh');
            }
            $page_data['data'] = $param1;
            $page_data['page_name']  = 'meet';
            $page_data['page_title'] = getEduAppGTLang('meet');
            $this->load->view('backend/index', $page_data);
        }

        //Admin dashboard function.
        function panel($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            $page_data['page_name']  = 'panel';
            $page_data['page_title'] = getEduAppGTLang('dashboard');
            $this->load->view('backend/index', $page_data);
        }
        
        //Read and manage news function.
        function news($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->crud->create_news();
                $this->crud->send_news_notify();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if ($param1 == 'update_panel') 
            {
                $this->crud->update_panel_news($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if ($param1 == 'create_video') 
            {
                $this->crud->create_video();
                $this->crud->send_news_notify();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if ($param1 == 'update_news') 
            {
                $this->crud->update_panel_news($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/news/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->delete_news($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if ($param1 == 'delete2') 
            {
                $this->crud->delete_news($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/news/', 'refresh');
            }
            $page_data['page_name'] = 'news';
            $page_data['page_title'] = getEduAppGTLang('news');
            $this->load->view('backend/index', $page_data);
        }
        
        //Private messages function.
        function message($param1 = 'message_home', $param2 = '', $param3 = '') 
        {
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            if ($param1 == 'send_new') 
            {
                $message_thread_code = $this->crud->send_new_private_message();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('message_sent'));
                redirect(base_url() . 'admin/message/message_read/' . $message_thread_code, 'refresh');
            }
            if ($param1 == 'send_reply') 
            {
                $this->crud->send_reply_message($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('reply_sent'));
                redirect(base_url() . 'admin/message/message_read/' . $param2, 'refresh');
            }
            if ($param1 == 'message_read') 
            {
                $page_data['current_message_thread_code'] = $param2; 
                $this->crud->mark_thread_messages_read($param2);
            }
            $page_data['infouser'] = $param2;
            $page_data['message_inner_page_name']   = $param1;
            $page_data['page_name']                 = 'message';
            $page_data['page_title']                = getEduAppGTLang('private_messages');
            $this->load->view('backend/index', $page_data);
        }
    
        //Chat groups function.
        function group($param1 = "group_message_home", $param2 = "", $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == "create_group") 
            {
                $this->crud->create_group();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/group/', 'refresh');
            }
            elseif($param1 == "delete_group")
            {
                $this->crud->deleteGroup($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/group/', 'refresh');
            }
            elseif ($param1 == "edit_group") 
            {
                $this->crud->update_group($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/group/', 'refresh');
            }
            else if ($param1 == 'group_message_read') 
            {
                $page_data['current_message_thread_code'] = $param2;
            }
            else if ($param1 == 'create_message_group') 
            {
                $page_data['current_message_thread_code'] = $param2;
            }
            else if ($param1 == 'update_group') 
            {
                $page_data['current_message_thread_code'] = $param2;
            }
            else if($param1 == 'send_reply')
            {
                $this->crud->send_reply_group_message($param2);
                $this->session->set_flashdata('flash_message', getEduAppGTLang('message_sent'));
                redirect(base_url() . 'admin/group/group_message_read/'.$param2, 'refresh');
            }
            $page_data['message_inner_page_name']   = $param1;
            $page_data['page_name']                 = 'group';
            $page_data['page_title']                = getEduAppGTLang('message_group');
            $this->load->view('backend/index', $page_data);
        }
    
        //Pending users function.
        function pending($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'pending';
            $page_data['page_title'] = getEduAppGTLang('pending_users');
            $this->load->view('backend/index', $page_data);
        }
    
        //Students reports function.
        function students_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['page_name']   = 'students_report';
            $page_data['page_title']  = getEduAppGTLang('students_report');
            $this->load->view('backend/index', $page_data);
        }
        //Office Admin Reports
        function admin_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['page_name']   = 'admin_report';
            $page_data['page_title']  = getEduAppGTLang('admin_report');
            $this->load->view('backend/index', $page_data);
        }
        //Admin - Management Reports
        function mgmt_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['page_name']   = 'mgmt_report';
            $page_data['page_title']  = getEduAppGTLang('mgmt_report');
            $this->load->view('backend/index', $page_data);
        }
        // Staff/Teachers Reports
        function teachers_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['page_name']   = 'teachers_report';
            $page_data['page_title']  = getEduAppGTLang('teachers_report');
            $this->load->view('backend/index', $page_data);
        }
    
        //General reports function.
        function general_reports($class_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']   = 'general_reports';
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['page_title']  = getEduAppGTLang('general_reports');
            $this->load->view('backend/index', $page_data);
        }

        //Manage birthdays function.
        function birthdays()
        {
            if ($this->session->userdata('admin_login') != 1)
            { 
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'birthdays';
            $page_data['page_title'] = getEduAppGTLang('birthdays');
            $this->load->view('backend/index', $page_data);
        }

        //Manage Librarians function.
        function librarian($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            { 
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createLibrarian();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/librarian/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->user->updateLibrarian($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/librarian/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateLibrarian($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/librarian_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->user->deleteLibrarian($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/librarian/', 'refresh');
            }
            $page_data['page_name']  = 'librarian';
            $page_data['page_title'] = getEduAppGTLang('librarians');
            $this->load->view('backend/index', $page_data);
        }
        
        //Create Invoice function.
        function new_payment($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'new_payment';
            $page_data['page_title'] = getEduAppGTLang('new_payment');
            $this->load->view('backend/index', $page_data);
        }

        //Manage accountants function.
        function accountant($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createAccountant();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/accountant/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->user->updateAccountant($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/accountant/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateAccountant($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/accountant_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->user->deleteAccountant($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/accountant/', 'refresh');
            }
            $page_data['page_name']  = 'accountant';
            $page_data['page_title'] = getEduAppGTLang('accountants');
            $this->load->view('backend/index', $page_data);
        }

        //System notifications function.
        function notifications($param1 = '', $param2 = '')
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            if($param1 == 'delete')
            {
                $this->db->where('id', $param2);
                $this->db->delete('notification');
                redirect(base_url() . 'admin/notifications/', 'refresh');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
            }
            $page_data['page_name']  =  'notifications';
            $page_data['page_title'] =  getEduAppGTLang('your_notifications');
            $this->load->view('backend/index', $page_data);
        }

        //Update academic settings function.
        function academic_settings($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'do_update') 
            {
                $this->crud->updateAcademicSettings();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/academic_settings/', 'refresh');
            }
            $page_data['page_name']  = 'academic_settings';
            $page_data['page_title'] = getEduAppGTLang('academic_settings');
            $page_data['settings']   = $this->db->get('settings')->result_array();
            $this->load->view('backend/index', $page_data);
        }
        
        //Check if student exist function.
        function query() 
        {
            if($_POST['b'] != "")
            {       
                $this->db->like('name' , $_POST['b']);
                $query = $this->db->get_where('student')->result_array();
                if(count($query) > 0)
                {
                    foreach ($query as $row) 
                    {
                        echo '<p style="text-align: left; color:#fff; font-size:14px;"><a style="text-align: left; color:#fff; font-weight: bold;" href="'.base_url().'admin/student_portal/'. $row['student_id'] .'/">'. $row['name'] .'</a>' ." &nbsp;".$status.""."</p>";
                    }
                } else{
                    echo '<p class="col-md-12" style="text-align: left; color: #fff; font-weight: bold; ">'.getEduAppGTLang('no_results').'</p>';
                }
            }
        }
    
        //Create Student function.
        function new_student($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(site_url('login'), 'refresh');
            }
            $page_data['page_name']  = 'new_student';
            $page_data['page_title'] = getEduAppGTLang('admissions');
            $this->load->view('backend/index', $page_data);
        }
        
        //Grade Leves function.
        function grade($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(site_url('login'), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createLevel();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/grade/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->academic->updateLevel($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/grade/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->academic->deleteLevel($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/grade/', 'refresh');
            }
            $page_data['page_name']  = 'grade';
            $page_data['page_title'] = getEduAppGTLang('grades');
            $this->load->view('backend/index', $page_data);
        }
    
        //All users and manage admin permissions function.
        function users($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'permissions')
            {
                $this->crud->setPermissions();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/users/', 'refresh');
            }
            $page_data['page_name']                 = 'users';
            $page_data['page_title']                = getEduAppGTLang('users');
            $this->load->view('backend/index', $page_data);
        }
        //Manage Super Admin function.
        function sadmins($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createAdmin();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sadmins/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sadmins/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/admin_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->db->where('admin_id', $param2);
                $this->db->delete('admin');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/sadmins/', 'refresh');
            }
            $page_data['page_name']     = 'superadmins';
            $page_data['page_title']    = getEduAppGTLang('SuperAdmins');
            $this->load->view('backend/index', $page_data);
        }
        
        //Manage Management function.
        function mgmt($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createAdmin();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/mgmt/', 'refresh');
            }
            if($param1 == 'download')
            {
                $this->user->downloadManagement();
            }         
            if($param1 == 'pdf_download')
            {   
                $this->user->downloadPDFManagement();
            }             
            if ($param1 == 'update') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/mgmt/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/admin_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->db->where('admin_id', $param2);
                $this->db->delete('admin');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/mgmt/', 'refresh');
            }
            $page_data['page_name']     = 'mgmt';
            $page_data['page_title']    = getEduAppGTLang('management');
            $this->load->view('backend/index', $page_data);
        }
        
        //Manage Admins function.
        function admins($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createAdmin();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/admins/', 'refresh');
            }
            if($param1 == 'download') 
            {
                $this->user->downloadAdmins();
            }         
            if($param1 == 'pdf_download')
            {   
                $this->user->downloadPDFAdmins();
            } 
            if ($param1 == 'update') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/admins/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateAdmin($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/admin_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->db->where('admin_id', $param2);
                $this->db->delete('admin');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/admins/', 'refresh');
            }
            $page_data['page_name']     = 'admins';
            $page_data['page_title']    = getEduAppGTLang('admins');
            $this->load->view('backend/index', $page_data);
        }

        function officestaff($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createOfficeStaff();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/officestaff/', 'refresh');
            }
            if($param1 == 'download') 
            {
                $this->user->downloadOfficeStaff();
            }         
            if($param1 == 'pdf_download')
            {   
                $this->user->downloadPDFOfficeStaff();
            }  
            if ($param1 == 'update') 
            {
                $this->user->updateOfficeStaff($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/officestaff/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateOfficeStaff($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/officestaff_update/'.$param2.'/', 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->db->where('staff_id', $param2);
                $this->db->delete('officestaff');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/officestaff/', 'refresh');
            }
            $page_data['page_name']     = 'officestaff';
            $page_data['page_title']    = getEduAppGTLang('Office_Staff');
            $this->load->view('backend/index', $page_data);
        }

        //Manage students function.
        function students($id = '',$stdyear = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $id = $this->input->post('class_id');
            $stdyear = $this->input->post('stdyear');
            if ($id == '')
            {
                //$id = $this->db->get('class')->first_row()->class_id;
                $id = "All";
            }
            if ($stdyear == '')
            {
                $stdyear = "I";
            }
            $page_data['page_name']   = 'students';
            $page_data['page_title']  = getEduAppGTLang('students');
            $page_data['class_id']  = $id;
            $page_data['stdyear']  = $stdyear;
            $this->load->view('backend/index', $page_data);
        }

        //Admin Profile function.
        function admin_profile($admin_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'admin_profile';
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $page_data['admin_id']  =  $admin_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Accountant profile function.
        function accountant_profile($accountant_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'accountant_profile';
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $page_data['accountant_id']  =  $accountant_id;
            $this->load->view('backend/index', $page_data);
        }
        //Accountant update profile function.
        function accountant_update($accountant_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'accountant_update';
            $page_data['page_title'] =  getEduAppGTLang('update_information');
            $page_data['accountant_id']  =  $accountant_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Accountant profile function.
        function officestaff_profile($staff_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'officestaff_profile';
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $page_data['staff_id']  =  $staff_id;
            $this->load->view('backend/index', $page_data);
        }    
        //Accountant update profile function.
        function officestaff_update($staff_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'officestaff_update';
            $page_data['page_title'] =  getEduAppGTLang('update_information');
            $page_data['staff_id']  =  $staff_id;
            $this->load->view('backend/index', $page_data);
        }        
        //Librarian Profile function.
        function librarian_profile($librarian_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'librarian_profile';
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $page_data['librarian_id']  =  $librarian_id;
            $this->load->view('backend/index', $page_data);
        }
    
        //Librarian update profile function.
        function librarian_update($librarian_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'librarian_update';
            $page_data['page_title'] =  getEduAppGTLang('librarian_update');
            $page_data['librarian_id']  =  $librarian_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Admin update profile function.
        function admin_update($admin_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'admin_update';
            $page_data['page_title'] =  getEduAppGTLang('update_information');
            $page_data['admin_id']  =  $admin_id;
            $this->load->view('backend/index', $page_data);
        }
    
        //Update account for Admin function.
        function update_account($admin_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                redirect(base_url(), 'refresh');
            }
            $output                  = $this->crud->getGoogleURL();
            $page_data['page_name']  = 'update_account';
            $page_data['output']     = $output;
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $this->load->view('backend/index', $page_data);
        }

        //Manage teachers function.
        function teachers($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'accept')
            {
                $this->user->acceptTeacher($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/teachers/', 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createTeacher();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/teachers/', 'refresh');
            }
            if($param1 == 'download')
            {   
                $this->user->downloadTeachers();
            } 
            if($param1 == 'pdf_download')
            {   
                $this->user->downloadPDFTeachers();
            } 
            if ($param1 == 'update') 
            {
                $this->user->updateTeacher($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/teachers/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateTeacher($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/teacher_update/'.$param2. '/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->user->deleteTeacher($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/teachers/', 'refresh');
            }
            $page_data['page_name']  = 'teachers';
            $page_data['page_title'] = getEduAppGTLang('teachers');
            $this->load->view('backend/index', $page_data);
        }
        
        //Teacher Profile function.
        function teacher_profile($teacher_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {            
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'teacher_profile';
            $page_data['page_title'] =  getEduAppGTLang('profile');
            $page_data['teacher_id']  =  $teacher_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Teacher Update info function.
        function teacher_update($teacher_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {            
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'teacher_update';
            $page_data['page_title'] =  getEduAppGTLang('update_information');
            $page_data['teacher_id']  =  $teacher_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Teacher Schedules function.
        function teacher_schedules($teacher_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {            
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'teacher_schedules';
            $page_data['page_title'] =  getEduAppGTLang('teacher_schedules');
            $page_data['teacher_id']  =  $teacher_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Teacher subjects function.
        function teacher_subjects($teacher_id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {            
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'teacher_subjects';
            $page_data['page_title'] =  getEduAppGTLang('teacher_subjects');
            $page_data['teacher_id']  =  $teacher_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Manage parents function.
        function parents($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
               redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->user->createParent();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/parents/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->user->updateParent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/parents/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateParent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/parent_update/'.$param2.'/', 'refresh');
            }
            if($param1 == 'accept')
            {
                $this->user->acceptParent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/parents/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->user->deleteParent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/parents/', 'refresh');
            }
            $page_data['page_title']  = getEduAppGTLang('parents');
            $page_data['page_name']  = 'parents';
            $this->load->view('backend/index', $page_data);
        }
    
        //Delete student homework delivery function.
        function delete_delivery($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 != '')
            {
                $this->academic->deleteDelivery($param1);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/homework_details/'.$param2.'/', 'refresh');
            }
        }
    
        //Notification center function.
        function notify($param1 = '', $param2 = '')
        {
          if ($this->session->userdata('admin_login') != 1)
          {
              redirect(base_url(), 'refresh');
          }
          if($param1 == 'send_emails')
          {
                $this->mail->sendEmailNotify();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('sent_successfully'));
                redirect(base_url() . 'admin/notify/', 'refresh');
            }
            if($param1 == 'sms')
            {       
                $this->crud->sendSMS();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('sent_successfully'));
                redirect(base_url() . 'admin/notify/', 'refresh');
            }
            $page_data['page_name']  = 'notify';
            $page_data['page_title'] = getEduAppGTLang('notifications');
            $this->load->view('backend/index', $page_data);
        }
    
        //Parent profile function.
        function parent_profile($parent_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['parent_id']  = $parent_id;
            $page_data['page_name']  = 'parent_profile';
            $page_data['page_title'] = getEduAppGTLang('profile');
            $this->load->view('backend/index', $page_data);
        }
        
        //Parent update profile function.
        function parent_update($parent_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['parent_id']  = $parent_id;
            $page_data['page_name']  = 'parent_update';
            $page_data['page_title'] = getEduAppGTLang('update_information');
            $this->load->view('backend/index', $page_data);
        }
        
        //Parent childs function.
        function parent_childs($parent_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['parent_id']  = $parent_id;
            $page_data['page_name']  = 'parent_childs';
            $page_data['page_title'] = getEduAppGTLang('parent_childs');
            $this->load->view('backend/index', $page_data);
        }
    
        //Delete Student function.
        function delete_student($student_id = '', $class_id = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $this->crud->deleteStudent($student_id);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
            redirect(base_url() . 'admin/students/', 'refresh');
        }
    
        //Attendance selector function.
        function attendance_selector()
        {
            $timestamp = $this->crud->attendanceSelector();
            redirect(base_url().'admin/attendance/'.$this->input->post('data').'/'.$timestamp,'refresh');
        }
    
        //Attendance Update function.
        function attendance_update($class_id = '' , $subject_id = '' , $timestamp = '')
        {        
            $this->crud->attendanceUpdate($class_id, $subject_id, $timestamp);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
            redirect(base_url().'admin/attendance/'.base64_encode($class_id.'-'.$subject_id).'/'.$timestamp , 'refresh');
        }
    
        //Database tools function.
        function database($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'restore')
            {
                $this->crud->import_db();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('restored'));
                redirect(base_url() . 'admin/database/', 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->create_backup();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('backup_created'));
                redirect(base_url() . 'admin/database/', 'refresh');
            }
            $page_data['page_name']                 = 'database';
            $page_data['page_title']                = getEduAppGTLang('database');
            $this->load->view('backend/index', $page_data);
        }
        //Designations Settings function.
        function designations($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->add_designation();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/designations/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateDesignation($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/designations/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteDesignation($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/designations/', 'refresh');
            }            
            $page_data['page_name']  = 'designations';
            $page_data['page_title'] = getEduAppGTLang('designations');
            $this->load->view('backend/index', $page_data);
        }
        //Teaching Subject Manager
        function teaching_subjects($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->add_teaching_subject();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/teaching_subjects/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateTeaching_subject($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/teaching_subjects/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteTeaching_subject($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/teaching_subjects/', 'refresh');
            }            
            $page_data['page_name']  = 'teaching_subjects';
            $page_data['page_title'] = getEduAppGTLang('teaching_subjects');
            $this->load->view('backend/index', $page_data);
        }        
        //Fee Type Manager
        function feetypes($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->add_feetype();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/feetypes/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateFeetype($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/feetypes/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteFeetype($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/feetypes/', 'refresh');
            }            
            $page_data['page_name']  = 'feetypes';
            $page_data['page_title'] = getEduAppGTLang('feetypes');
            $this->load->view('backend/index', $page_data);
        }        
        //SMS API's Settings function.
        function sms($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'update')
            {
                $this->crud->smsStatus();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sms/', 'refresh');
            }
            if($param1 == 'msg91')
            {
                $this->crud->msg91();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sms/', 'refresh');
            }
            if($param1 == 'clickatell')
            {
                $this->crud->clickatellSettings();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sms/', 'refresh');
            }
            if($param1 == 'twilio') 
            {
                $this->crud->twilioSettings();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sms/', 'refresh');
            }
            if($param1 == 'services') 
            {
                $this->crud->services();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/sms/', 'refresh');
            }
            $page_data['page_name']  = 'sms';
            $page_data['page_title'] = getEduAppGTLang('sms');
            $this->load->view('backend/index', $page_data);
        }
    
        //Email settings function.
        function email($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'template')
            {
                $this->crud->emailTemplate($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/email/', 'refresh');
            }
            $page_data['page_name']  = 'email';
            $page_data['current_email_template_id']  = 1;
            $page_data['page_title'] = getEduAppGTLang('email_settings');
            $this->load->view('backend/index', $page_data);
        }
    
        //View teacher report function.
        function view_teacher_report()
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'view_teacher_report';
            $page_data['page_title'] = getEduAppGTLang('teacher_report');
            $this->load->view('backend/index', $page_data);
        }
        
        //System translation function.
        function translate($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'update') 
            {
                $page_data['edit_profile']  = $param2;
            }
            if ($param1 == 'update_language') 
            {
                $this->crud->updateLang($param2);
            }
            if ($param1 == 'add') 
            {
                $this->crud->createLang();
                $this->session->set_flashdata('flash_message', getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/translate/', 'refresh');
            }
            $page_data['page_name']  = 'translate';
            $page_data['page_title'] = getEduAppGTLang('translate');
            $this->load->view('backend/index', $page_data);
        }
        
        //Manage polls function.
        function polls($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->createPoll();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if($param1 == 'create_wall')
            {
                $this->crud->createPoll();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/polls/', 'refresh');
            }
            if($param1 == 'response')
            {
                $this->crud->pollReponse();
            }
            if($param1 == 'delete')
            {
                $this->crud->deletePoll($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/panel/', 'refresh');
            }
            if($param1 == 'delete2')
            {
                $this->crud->deletePoll($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/polls/', 'refresh');
            }
            $page_data['page_name']  = 'polls';
            $page_data['page_title'] = getEduAppGTLang('polls');
            $this->load->view('backend/index', $page_data);
        }
    
        //View poll details function.
        function view_poll($code = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['code'] = $code;
            $page_data['page_name']  = 'view_poll';
            $page_data['page_title'] = getEduAppGTLang('poll_details');
            $this->load->view('backend/index', $page_data);
        }
        
        //New poll function.
        function new_poll($code = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'new_poll';
            $page_data['page_title'] = getEduAppGTLang('new_poll');
            $this->load->view('backend/index', $page_data);
        }

        //Manage Visitors function.
        function visitors($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'create')
            {
                $this->crud->createVisitor();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/view_visitor/', 'refresh');
            }
            if($param1 == 'update')
            {
                $this->crud->updateVisitor($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/visitors/', 'refresh');
            }
            $page_data['page_name']  = 'visitors';
            $page_data['page_title'] = getEduAppGTLang('visitors');
            $this->load->view('backend/index', $page_data);
        }
    
        //View visitor details function.
        function view_visitor($code = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['code'] = $code;
            $page_data['page_name']  = 'view_visitor';
            $page_data['page_title'] = getEduAppGTLang('visitor_details');
            $this->load->view('backend/index', $page_data);
        }
        
        //New visitor function.
        function new_visitor($code = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'new_visitor';
            $page_data['page_title'] = getEduAppGTLang('new_visitor');
            $this->load->view('backend/index', $page_data);
        }
        //visitor Left function.
        function exit_visitor($visitor_id = '')
        {
            //echo $visitor_id;
            $this->user->updateVisitor($visitor_id);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
            redirect(base_url() . 'admin/view_visitor/', 'refresh');
        }        
        
        //Teacher Routine function.
        function teacher_routine()
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $teacher_id = $this->input->post('teacher_id');
            $page_data['page_name']  = 'teacher_routine';
            $page_data['teacher_id']  = $teacher_id;
            $page_data['page_title'] = getEduAppGTLang('teacher_routine');
            $this->load->view('backend/index', $page_data);
        }
    
        //Student Profile function.
        function student_portal($student_id, $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->runningYear))->row()->class_id;
            $page_data['page_name']  = 'student_portal';
            $page_data['page_title'] =  getEduAppGTLang('student_portal');
            $page_data['student_id'] =  $student_id;
            $page_data['class_id']   =  $class_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Student update function.
        function student_update($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_update';
            $page_data['page_title'] =  getEduAppGTLang('student_portal');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
        //Student's Parent Form Page function.
        function student_parent_update($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_parent_update';
            $page_data['page_title'] =  getEduAppGTLang('student_parent_update');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
        //Student's Misc/Dises Form Page function.
        function student_misc_update($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_misc_update';
            $page_data['page_title'] =  getEduAppGTLang('student_misc_update');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }        
    
        //Sutdent invoices function.
        function student_invoices($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_invoices';
            $page_data['page_title'] =  getEduAppGTLang('student_invoices');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
        
        //Student marks function.
        function student_marks($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_marks';
            $page_data['page_title'] =  getEduAppGTLang('student_marks');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
    
        //Student attendance report selector function.
        function student_attendance_report_selector()
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['year']       = $this->input->post('year');
            $data['month']      = $this->input->post('month');
            redirect(base_url().'admin/student_profile_attendance/'.$this->input->post('student_id').'/'.$data['class_id'].'/'.$data['subject_id'].'/'.$data['month'].'/'.$data['year'].'/','refresh');
        }
        
        //Student Profile Attendance function.
        function student_profile_attendance($student_id = '', $param1='', $param2 = '', $param3 = '', $param4 = '', $param5 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_profile_attendance';
            $page_data['page_title'] =  getEduAppGTLang('student_attendance');
            $page_data['student_id'] =  $student_id;
            $page_data['subject_id'] =  $param3;
            $page_data['class_id'] =  $param1;
            $page_data['month'] =  $param4;
            $page_data['year'] =  $param5;
            $this->load->view('backend/index', $page_data);
        }
        
        //Student profile report function.
        function student_profile_report($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_profile_report';
            $page_data['page_title'] =  getEduAppGTLang('behavior');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
    
        //Student info function.
        function student_info($student_id = '', $param1='')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'student_info';
            $page_data['page_title'] =  getEduAppGTLang('student_portal');
            $page_data['student_id'] =  $student_id;
            $this->load->view('backend/index', $page_data);
        }
    
        //My account function.
        function my_account($param1 = "", $page_id = "")
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                $this->session->set_userdata('last_page' , current_url());
                redirect(base_url(), 'refresh');
            }     
            if($param1 == 'remove_facebook')
            {
                $this->user->removeFacebook();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('facebook_delete'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }
            if($param1 == '1')
            {
                $this->session->set_flashdata('error_message' , getEduAppGTLang('google_err'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }
            if($param1 == '3')
            {
                $this->session->set_flashdata('error_message' , getEduAppGTLang('facebook_err'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }
            if($param1 == '2')
            {
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('google_true'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }
            if($param1 == '4')
            {
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('facebook_true'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }  
            if($param1 == 'remove_google')
            {
                $this->user->removeGoogle();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('google_delete'));
                redirect(base_url() . 'admin/my_account/', 'refresh');
            }
            if ($param1 == 'update_profile') 
            {
                $this->user->updateCurrentAdmin();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/update_account/', 'refresh');
            }
            $output                 = $this->crud->getGoogleURL();
            $data['page_name']      = 'my_account';
            $data['output']         = $output;
            $data['page_title']     = getEduAppGTLang('profile');
            $this->load->view('backend/index', $data);
        }
        
        //Book request function.
        function book_request($param1 = "", $param2 = "")
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == "accept")
            {
                $this->academic->acceptBook($param2);
                $this->session->set_flashdata('flash_message', getEduAppGTLang('request_accepted_successfully'));
                redirect(site_url('admin/book_request/'), 'refresh');
            }
            if ($param1 == "reject")
            {
                $this->academic->rejectBook($param2);
                $this->session->set_flashdata('flash_message', getEduAppGTLang('request_rejected_successfully'));
                redirect(site_url('admin/book_request/'), 'refresh');
            }
            $data['page_name']  = 'book_request';
            $data['page_title'] = getEduAppGTLang('book_request');
            $this->load->view('backend/index', $data);
        }
    
        //Permissions request for teachers function.
        function request($param1 = "", $param2 = "")
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }           
            if ($param1 == "accept")
            {
                $this->crud->acceptRequest($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/request/', 'refresh');
            }
            if ($param1 == "reject")
            {
                $this->crud->rejectRequest($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('rejected_successfully'));
                redirect(base_url() . 'admin/request/', 'refresh');
            }
            $data['page_name']  = 'request';
            $data['page_title'] = getEduAppGTLang('permissions');
            $this->load->view('backend/index', $data);
        }
    
        //Permissions request for students function.
        function request_student($param1 = "", $param2 = "")
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            if ($param1 == "accept")
            {
                $this->crud->acceptStudentRequest($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/request/', 'refresh');
            }
            if ($param1 == "reject")
            {
                $this->crud->rejectStudentRequest($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('rejected_successfully'));
                redirect(base_url() . 'admin/request/', 'refresh');
            }
            if($param1 == 'delete')
            {
                $this->crud->deletePermission($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/request_student/', 'refresh');
            }
            if($param1 == 'delete_teacher')
            {
                $this->crud->deleteTeacherPermission($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/request_student/', 'refresh');
            }
            $data['page_name']  = 'request_student';
            $data['page_title'] = getEduAppGTLang('reports');
            $this->load->view('backend/index', $data);
        }
    
        //Create message for reports function.
        function create_report_message($code = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $this->crud->createReportMessage();
        }  
    
        //View report function.
        function view_report($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            if($param1 == 'update')
            {
                $this->crud->updateReport($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/view_report/'.$param2, 'refresh');
            }
            $page_data['report_code'] = $param1;
            $page_data['page_title']  =   getEduAppGTLang('report_details');
            $page_data['page_name']   = 'view_report';
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage Online exam status function.
        function manage_online_exam_status($online_exam_id = "", $status = "", $data)
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }   
            $this->crud->manage_online_exam_status($online_exam_id, $status);
            redirect(base_url() . 'admin/online_exams/'.$data."/", 'refresh');
        }
    
        //Online exams function.
        function online_exams($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }   
            if ($param1 == 'edit') 
            {
                if ($this->input->post('class_id') > 0 && $this->input->post('subject_id') > 0) {
                    $this->crud->update_online_exam();
                    $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                    redirect(base_url() . 'admin/exam_edit/' . $this->input->post('online_exam_id'), 'refresh');
                }
                else{
                    $this->session->set_flashdata('error_message' , getEduAppGTLang('error_updated'));
                    redirect(base_url() . 'admin/exam_edit/' . $this->input->post('online_exam_id'), 'refresh');
                }
            }
            if ($param1 == 'questions') 
            {
                $this->crud->add_questions();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/exam_questions/' . $param2 , 'refresh');
            }
            if ($param1 == 'delete_questions') 
            {
                $this->db->where('question_id', $param2);
                $this->db->delete('questions');
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/exam_questions/'.$param3, 'refresh');
            }
            if($param1 == 'delete')
            {
                $this->academic->deleteOnlineExam($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/online_exams/'.$param3."/", 'refresh');
            }
            $page_data['data']       = $param1;
            $page_data['page_name']  = 'online_exams';
            $page_data['page_title'] = getEduAppGTLang('online_exams');
            $this->load->view('backend/index', $page_data);
        }
    
        //Update online exam function.
        function exam_edit($exam_code= '') 
        { 
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }   
            $page_data['online_exam_id'] = $exam_code;
            $page_data['page_name']      = 'exam_edit';
            $page_data['page_title']     = getEduAppGTLang('update_exam');
            $this->load->view('backend/index', $page_data);
        }
    
        //View exam results function.
        function exam_results($exam_code = '') 
        { 
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }   
            $page_data['online_exam_id'] = $exam_code;
            $page_data['page_name']      = 'exam_results';
            $page_data['page_title']     = getEduAppGTLang('exams_results');
            $this->load->view('backend/index', $page_data);
        }
    
        //Homework details function.
        function homeworkroom($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'file') 
            {
                $page_data['room_page']    = 'homework_file';
                $page_data['homework_code'] = $param2;
            }  
            else if ($param1 == 'details') 
            {
                $page_data['room_page'] = 'homework_details';
                $page_data['homework_code'] = $param2;
            }
            else if ($param1 == 'edit') 
            {
                $page_data['room_page'] = 'homework_edit';
                $page_data['homework_code'] = $param2;
            }
            $page_data['homework_code'] =   $param1;
            $page_data['page_name']   = 'homework_room'; 
            $page_data['page_title']  = getEduAppGTLang('homework');
            $this->load->view('backend/index', $page_data);
        }
    
        //Homework Edit function.
        function homework_edit($homework_code = '') 
        {   
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            } 
            $page_data['homework_code'] = $homework_code;
            $page_data['page_name'] = 'homework_edit';
            $page_data['page_title'] = getEduAppGTLang('homework');
            $this->load->view('backend/index', $page_data);
        }
    
        //Single Homework Function.
        function single_homework($param1 = '', $param2 = '') 
        {
           if ($this->session->userdata('admin_login') != 1)
           {
                redirect(base_url(), 'refresh');
           }
           $page_data['answer_id'] = $param1;
           $page_data['page_name'] = 'single_homework';
           $page_data['page_title'] = getEduAppGTLang('homework');
           $this->load->view('backend/index', $page_data);
        }
    
        //Homework details function.
        function homework_details($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['homework_code'] = $param1;
            $page_data['page_name']     = 'homework_details';
            $page_data['page_title']    = getEduAppGTLang('homework_details');
            $this->load->view('backend/index', $page_data);
        }
        
        //New online exam function.
        function new_exam($data = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['data'] = $data;
            $page_data['page_name']  = 'new_exam';
            $page_data['page_title'] = getEduAppGTLang('new_exam');
            $this->load->view('backend/index', $page_data);
        }
    
        //Homework function.
        function homework($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $homework_code = $this->academic->createHomework();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/homeworkroom/' . $homework_code .'/', 'refresh');
            }
            if($param1 == 'update')
            {
                $this->academic->updateHomework($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/homework_edit/' . $param2 , 'refresh');
            }
            if($param1 == 'review')
            {
                $this->academic->reviewHomework();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/homework_details/' . $param2 , 'refresh');
            }
            if($param1 == 'single')
            {
                $this->academic->singleHomework();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/single_homework/' . $this->input->post('id') , 'refresh');
            }
            if ($param1 == 'edit') 
            {
                $this->crud->update_homework($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/homeworkroom/edit/' . $param2 , 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->crud->delete_homework($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/homework/'.$param3."/", 'refresh');
            }
            $page_data['data'] = $param1;
            $page_data['page_name'] = 'homework';
            $page_data['page_title'] = getEduAppGTLang('homework');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage Forums funcion.
        function forum($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                $this->session->set_userdata('last_page' , current_url());
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createForum();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/forum/' . $param2."/" , 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->academic->updateForum($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/edit_forum/' . $param2 , 'refresh');
            }
            if ($param1 == 'delete')
            {
                $this->crud->delete_post($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/forum/'.$param3."/" , 'refresh');
            }
            $page_data['data'] = $param1;
            $page_data['page_name'] = 'forum';
            $page_data['page_title'] = getEduAppGTLang('forum');
            $this->load->view('backend/index', $page_data);
        }
    
        //Study Material Function.
        function study_material($task = "", $document_id = "", $data = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                $this->session->set_userdata('last_page' , current_url());
                redirect(base_url(), 'refresh');
            } 
            if ($task == "create")
            {
                $this->academic->createMaterial();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_uploaded'));
                redirect(base_url() . 'admin/study_material/'.$document_id."/" , 'refresh');
            }
            if ($task == "delete")
            {
                $this->crud->delete_study_material_info($document_id);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/study_material/'.$data."/");
            }
            $page_data['data']          = $task;
            $page_data['page_name']     = 'study_material';
            $page_data['page_title']    = getEduAppGTLang('study_material');
            $this->load->view('backend/index', $page_data);
        }
    
        //Edit forum function
        function edit_forum($code = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'edit_forum';
            $page_data['page_title'] = getEduAppGTLang('update_forum');
            $page_data['code']   = $code;
            $this->load->view('backend/index', $page_data);    
        }
    
        //Forum details function.
        function forumroom($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'comment') 
            {
                $page_data['room_page']    = 'comments';
                $page_data['post_code'] = $param2; 
            }
            else if ($param1 == 'posts') 
            {
                $page_data['room_page'] = 'post';
                $page_data['post_code'] = $param2; 
            }
            else if ($param1 == 'edit') 
            {
                $page_data['room_page'] = 'post_edit';
                $page_data['post_code'] = $param2;
            }
            $page_data['page_name']   = 'forum_room'; 
            $page_data['post_code']   = $param1;
            $page_data['page_title']  = getEduAppGTLang('forum');
            $this->load->view('backend/index', $page_data);
        }
    
        //Forum Message Function.
        function forum_message($param1 = '', $param2 = '', $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'add') 
            {
                $this->crud->create_post_message($this->input->post('post_code'));
            }
        }
        
        //Manage multiple choice questions function.
        function manage_multiple_choices_options() 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['number_of_options'] = $this->input->post('number_of_options');
            $this->load->view('backend/admin/manage_multiple_choices_options', $page_data);
        }
    
        //Manage image questions function.
        function manage_image_options() 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['number_of_options'] = $this->input->post('number_of_options');
            $this->load->view('backend/admin/manage_image_options', $page_data);
        }
    
        //Load question type function.
        function load_question_type($type, $online_exam_id) 
        {
            if ($this->session->userdata('admin_login') != 1){
                redirect(base_url(), 'refresh');
            }
            $page_data['question_type']  = $type;
            $page_data['online_exam_id'] = $online_exam_id;
            $this->load->view('backend/admin/online_exam_add_'.$type, $page_data);
        }
        
        //Online exam questions function.
        function manage_online_exam_question($online_exam_id = "", $task = "", $type = "")
        {
            if ($this->session->userdata('admin_login') != 1){
                redirect(base_url(), 'refresh');
            }
            if ($task == 'add') {
                if ($type == 'multiple_choice') {
                    $this->crud->add_multiple_choice_question_to_online_exam($online_exam_id);
                }
                elseif ($type == 'true_false') {
                    $this->crud->add_true_false_question_to_online_exam($online_exam_id);
                }
                elseif ($type == 'image') {
                    $this->crud->add_image_question_to_online_exam($online_exam_id);
                }
                elseif ($type == 'fill_in_the_blanks') {
                    $this->crud->add_fill_in_the_blanks_question_to_online_exam($online_exam_id);
                }
                redirect(base_url() . 'admin/examroom/'.$online_exam_id, 'refresh');
            }
        }
    
        //Online exam details function.
        function examroom($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']   = 'exam_room'; 
            $page_data['online_exam_id']  = $param1;
            $page_data['page_title']  = getEduAppGTLang('online_exams');
            $this->load->view('backend/index', $page_data);
        }
        
        //Create Online Exam function.
        function create_online_exam($info = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $this->academic->createOnlineExam();
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
            redirect(base_url().'admin/online_exams/'.$info."/", 'refresh');
        }
        
        //Manage invoices function.
        function invoice($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'bulk') 
            {
                $this->payment->createBulkInvoice();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/students_payments/', 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->payment->singleInvoice();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/students_payments/', 'refresh');
            }
            if ($param1 == 'do_update') 
            {
                $this->payment->updateInvoice($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/students_payments/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->payment->deleteInvoice($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/students_payments/', 'refresh');
            }
        }
        
        function downloadaccountreport()
        {
            $dtype = $_REQUEST['dtype'];
            $datea = $this->payment->genratereportsdownload($dtype);
            
            
            
            }
        //Get all students to Bulk invoice function.
        function get_class_students_mass($class_id = '')
        {
            $this->crud->fetchStudents($class_id);
        }
        
        //Delete question from online exam function.
        function delete_question_from_online_exam($question_id)
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(site_url('login'), 'refresh');   
            }
            $online_exam_id = $this->db->get_where('question_bank', array('question_bank_id' => $question_id))->row()->online_exam_id;
            $this->crud->delete_question_from_online_exam($question_id);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
            redirect(base_url() . 'admin/examroom/'.$online_exam_id, 'refresh');
        }
        
        //Update online exam question function.
        function update_online_exam_question($question_id = "", $task = "", $online_exam_id = "") 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(site_url('login'), 'refresh');   
            }
            $online_exam_id = $this->db->get_where('question_bank', array('question_bank_id' => $question_id))->row()->online_exam_id;
            $type = $this->db->get_where('question_bank', array('question_bank_id' => $question_id))->row()->type;
            if ($task == "update") {
                if ($type == 'multiple_choice') {
                    $this->crud->update_multiple_choice_question($question_id);
                }
                elseif($type == 'true_false'){
                    $this->crud->update_true_false_question($question_id);
                }
                elseif($type == 'image'){
                    $this->crud->update_image_question($question_id);
                }
                elseif($type == 'fill_in_the_blanks'){
                    $this->crud->update_fill_in_the_blanks_question($question_id);
                }
                redirect(base_url() . 'admin/examroom/'.$online_exam_id, 'refresh');
            }
            $page_data['question_id'] = $question_id;
            $page_data['page_name'] = 'update_online_exam_question';
            $page_data['page_title'] = getEduAppGTLang('update_questions');
            $this->load->view('backend/index', $page_data);
        }
    
        //Search query function.
        function search_query($search_key = '') 
        {        
            if ($_POST)
            {
                redirect(base_url() . 'admin/search_results?query=' . base64_encode($this->input->post('search_key')), 'refresh');
            }
        }
    
        //Search results function.
        function search_results()
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if ($_GET['query'] == "")
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['search_key'] =  $_GET['query'];
            $page_data['page_name']  =  'search_results';
            $page_data['page_title'] =  getEduAppGTLang('search_results');
            $this->load->view('backend/index', $page_data);
        }
    
        //Invoice details function.
        function invoice_details($id = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $page_data['invoice_id'] = $id;
            $page_data['page_title'] = getEduAppGTLang('invoice_details');
            $page_data['page_name']  = 'invoice_details';
            $this->load->view('backend/index', $page_data);
        }
    
        //Loking behavior report function.
        function looking_report($report_code = '') 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            $page_data['code'] = $report_code;
            $page_data['page_name'] = 'looking_report';
            $page_data['page_title'] = getEduAppGTLang('report_details');
            $this->load->view('backend/index', $page_data);
        }
        
        function dowloadaccountreport($param1 = '', $param2 = '', $param3 = '')
        {
            
            if($param1 == 'account_excle_download')
            {
                $this->payment->downloadAccountReportsE();
            }         
            if($param1 == 'account_pdf_download')
            {   
                $this->payment->downloadAccountReportsP();
            }            
        }
        //Manage students function.
        function student($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
               redirect(base_url(), 'refresh');
            }
            if($param1 == 'reject')
            {
                $this->user->rejectStudent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/pending/', 'refresh');
            }
            if($param1 == 'excel')
            {
                $this->user->downloadExcel();
            }
            if($param1 == 'download')
            {
                $this->user->downloadStudents();
            }         
            if($param1 == 'pdf_download')
            {   
                $this->user->downloadPDFStudents();
            }            
            if ($param1 == 'addmission') 
            {
                $student_id = $this->user->studentAdmission();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                if($this->input->post('download_pdf') == '1')
                {
                   redirect(base_url() . 'admin/download_file/'.$student_id.'/'.base64_encode($this->input->post('password')), 'refresh');
                }
                else
                {
                    redirect(base_url() . 'admin/students/', 'refresh');
                }
            }
            if ($param1 == 'do_update') 
            {
                $this->user->updateStudent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/student_update/'. $param2.'/', 'refresh');
            }
            if ($param1 == 'create_student_parent') 
            {
                $this->user->create_student_parent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/student_parent_update/'. $param2.'/', 'refresh');
            }
            if ($param1 == 'update_student_parent') 
            {
                $this->user->update_student_parent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/student_parent_update/'. $param3.'/', 'refresh');
            }
            if ($param1 == 'student_misc_update') 
            {
                $this->user->student_misc_update($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/student_misc_update/'. $param2.'/', 'refresh');
            }            
            if ($param1 == 'do_updates') 
            {
                $this->user->updateModalStudent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/students/', 'refresh');
            }
            if($param1 == 'accept')
            {
                $this->user->acceptStudent($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/students/', 'refresh');
            }
            if($param1 == 'bulk')
            {
                $this->user->bulkStudents();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/students/', 'refresh');
            }
        }
    
        //Promote students function.
        function student_promotion($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'promote') 
            {
                $this->academic->promoteStudents();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_promoted'));
                redirect(base_url() . 'admin/student_promotion' , 'refresh');
            }
            $page_data['page_title']    = getEduAppGTLang('student_promotion');
            $page_data['page_name']  = 'student_promotion';
            $this->load->view('backend/index', $page_data);
        }
    
        //Get students to promote function.
        function get_students_to_promote($class_id_from = '' , $class_id_to  = '', $running_year  = '', $promotion_year = '')
        {
            $page_data['class_id_from']     =   $class_id_from;
            $page_data['class_id_to']       =   $class_id_to;
            $page_data['running_year']      =   $running_year;
            $page_data['promotion_year']    =   $promotion_year;
            $this->load->view('backend/admin/student_promotion_selector' , $page_data);
        }
    
        //View marks function.
        function view_marks($student_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $class_id                = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->runningYear))->row()->class_id;
            $page_data['class_id']   =   $class_id;
            $page_data['page_name']  = 'view_marks';
            $page_data['page_title'] = getEduAppGTLang('marks');
            $page_data['student_id'] = $student_id;
            $this->load->view('backend/index', $page_data);    
        }
    
        //Subject marks function.
        function subject_marks($data = '') 
         {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['data'] = $data;
            $page_data['page_name']    = 'subject_marks';
            $page_data['page_title']   = getEduAppGTLang('subject_marks');
            $this->load->view('backend/index',$page_data);
        }
         
        //Subject dashboard function.
        function subject_dashboard($data = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['data'] = $data;
            $page_data['page_name']    = 'subject_dashboard';
            $page_data['page_title']   = getEduAppGTLang('subject_dashboard');
            $this->load->view('backend/index',$page_data);
        }
    
        //Manage subjects function.
        function courses($param1 = '', $param2 = '' , $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createCourse();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/cursos/'.base64_encode($param2)."/", 'refresh');
            }
            if ($param1 == 'update_labs') 
            {
                $class_id = $this->db->get_where('subject', array('subject_id' => $param2))->row()->class_id;
                $this->academic->updateCourseActivity($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/upload_marks/'.base64_encode($class_id."-".$param2).'/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->academic->updateCourse($param2);
                $class_id = $this->db->get_where('subject', array('subject_id' => $param2))->row()->class_id;
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/cursos/'.base64_encode($class_id."-".$param2)."/", 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->academic->deleteCourse($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/cursos/', 'refresh');
            }
        }
        
        //Online exam result function.
        function online_exam_result($param1 = '', $param2 = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(site_url('login'), 'refresh');
            }
            $page_data['page_name']  = 'online_exam_result';
            $page_data['param2']     = $param1;
            $page_data['student_id'] = $param2;
            $page_data['page_title'] = getEduAppGTLang('online_exam_results');
            $this->load->view('backend/index', $page_data);
        }
        
        //Manage your classes.
        function manage_classes($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createClass();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/grados/', 'refresh');
            }
            if ($param1 == 'update')
            {
                $this->academic->updateClass($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/grados/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->academic->deleteClass($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/grados/', 'refresh');
            }
        }

        //Get subjects by classId function
        function get_subject($class_id = '') 
        {
            $subject = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
            foreach ($subject as $row) 
            {
                echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
            }
        }
    
        //Download virtual book function.
        function download_book($libro_code = '')
        {
            $file_name = $this->db->get_where('libreria', array('libro_code' => $libro_code))->row()->file_name;
            $this->load->helper('download');
            $data = file_get_contents("public/uploads/libreria/" . $file_name);
            $name = $file_name;
            force_download($name, $data);
        }
        
        function course_manager($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $class = $this->input->post('class_id');
            if ($class == '')
            {
                $class = $this->db->get('class')->first_row()->class_id;
            }
            $page_data['page_name']  = 'course_manager';
            $page_data['page_title'] = getEduAppGTLang('course_manager');
            $page_data['class_id']   = $class;
            $this->load->view('backend/index', $page_data);    
        }

        //Get Students from class and Year function.
        function get_class_year_students($class_id = '', $stdyear = '')
        {
            $students = $this->db->get_where('student' , array('combination' =>$_POST['class_id'],'stdyear' => $_POST['stdyear']))->result_array();
            $html = '';
            foreach ($students as $students_filter_row) 
            {
                $html .= '<option value="' . $students_filter_row['student_id'] . '">' . $students_filter_row['first_name'] . '</option>';
            }
            echo $html;
        }        
        //Manage semesters function.
        function semesters($param1 = '', $param2 = '' , $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createSemester();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/semesters/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->academic->updateSemester($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/semesters/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->academic->deleteSemester($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/semesters/', 'refresh');
            }
            $page_data['page_name']  = 'semester';
            $page_data['page_title'] = getEduAppGTLang('semesters');
            $this->load->view('backend/index', $page_data);
        }

        //Update Book Function.
        function update_book($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['book_id'] = $param1;
            $page_data['page_name']  =   'update_book';
            $page_data['page_title'] = getEduAppGTLang('update_book');
            $this->load->view('backend/index', $page_data);
        }
        
        //Upload your marks function.
        function upload_marks($datainfo = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param2 != ""){
                $page = $param2;
            }else{
                $page = $this->db->get('exam')->first_row()->exam_id;
            }
            $this->academic->uploadMarks($datainfo,$page);
            $page_data['exam_id'] = $page;
            $page_data['data'] = $datainfo;
            $page_data['page_name']  =   'upload_marks';
            $page_data['page_title'] = getEduAppGTLang('upload_marks');
            $this->load->view('backend/index', $page_data);
        }

        //Update marks function.
        function marks_update($exam_id = '' , $class_id = '' ,$subject_id = '')
        {
            $info = $this->academic->updateMarks($exam_id, $class_id, $subject_id);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
            redirect(base_url().'admin/upload_marks/'.$info.'/'.$exam_id.'/' , 'refresh');
        }

        //Tabulation sheet function.
        function tab_sheet_print($class_id  = '', $subject_id = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']    = $class_id;
            $page_data['exam_id']     = $exam_id;
            $page_data['subject_id']  = $subject_id;
            $this->load->view('backend/admin/tab_sheet_print' , $page_data);
        }
    
        //Manage Class Routine.
        function class_routine($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->academic->createRoutine();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/class_routine_view/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->academic->updateRoutine($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/class_routine_view/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->academic->deleteRoutine($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/class_routine_view/' . $class_id, 'refresh');
            } 
        }
    
        //Class routine view function.
        function class_routine_view($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $id = $this->input->post('class_id');
            if ($id == '')
            {
                $id = $this->db->get('class')->first_row()->class_id;
            }
            $page_data['page_name']  = 'class_routine_view';
            $page_data['id']         =   $id;
            $page_data['page_title'] = getEduAppGTLang('class_routine');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage attendance function.
        function attendance($param1 = '', $param2 = '')
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['data']       = $param1;
            $page_data['timestamp']  = $param2;
            $page_data['page_name']  =  'attendance';
            $page_data['page_title'] =  getEduAppGTLang('attendance');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage attendance function.
        function manage_attendance($class_id = '' , $timestamp = '')
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['class_id'] = $class_id;
            $page_data['timestamp'] = $timestamp;
            $page_data['page_name'] = 'manage_attendance';
            $page_data['page_title'] = getEduAppGTLang('attendance');
            $this->load->view('backend/index', $page_data);
        }
        //Attendance report function.
        function attendance_report($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '') 
        {
            if($param1 == 'check')
            {
                $data['class_id']    = $this->input->post('class_id');
                $data['subject_id']  = $this->input->post('subject_id');
                $data['year']        = $this->input->post('year');
                $data['month']       = $this->input->post('month');
                redirect(base_url().'admin/attendance_report/'.$data['class_id'].'/'.$data['subject_id'].'/'.$data['month'].'/'.$data['year'],'refresh');
            }
            $page_data['class_id']    = $param1;
            $page_data['subject_id']  = $param3;
            $page_data['month']       = $param4;
            $page_data['year']        = $param5;
            $page_data['page_name']   = 'attendance_report';
            $page_data['page_title']  = getEduAppGTLang('attendance_report');
            $this->load->view('backend/index',$page_data);
        }
        //Tabulation report function.
        function tabulation_report($param1 = '', $param2 = '')
        {
          if ($this->session->userdata('admin_login') != 1)
          {
            redirect(base_url(), 'refresh');
          }
          $page_data['class_id']   = $this->input->post('class_id');
          $page_data['subject_id']   = $this->input->post('subject_id');
          $page_data['page_name']   = 'tabulation_report';
          $page_data['page_title']  = getEduAppGTLang('tabulation_report');
          $this->load->view('backend/index', $page_data);
        }
        
        //Accounting report function.
        function accounting_report($param1 = '', $param2 = '')
        {
          if ($this->session->userdata('admin_login') != 1)
          {
            redirect(base_url(), 'refresh');
          }
          $page_data['page_name']   = 'accounting_report';
          $page_data['page_title']  = getEduAppGTLang('accounting_report');
          $this->load->view('backend/index', $page_data);
        }
        
        function accounting_genrate_report()
        {
            $datea = $this->payment->genratereports();
          echo  json_encode($datea) ;
        }
         
        //Marks report function.
        function marks_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'generate')
            {
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/marks_report/', 'refresh');
            }
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['student_id']   = $this->input->post('student_id');
            $page_data['exam_id']   = $this->input->post('exam_id');
            $page_data['page_name']   = 'marks_report';
            $page_data['page_title']  = getEduAppGTLang('marks_report');
            $this->load->view('backend/index', $page_data);
        }
    
        //Report attendance view function.
        function report_attendance_view($class_id = '' , $month = '', $year = '') 
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['class_id']   = $class_id;
            $page_data['month']      = $month;
            $page_data['year']       = $year;
            $page_data['page_name']  = 'report_attendance_view';
            $page_data['page_title'] = getEduAppGTLang('attendance_report');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage behavior report function.
        function create_report($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'send')
            {
                $this->academic->createReport();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/request_student/', 'refresh');
            }
            if($param1 == 'response')
            {
                $this->academic->reportResponse();
            }
            if($param1 == 'update')
            {
                $this->academic->updateReport($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/looking_report/'.$param2, 'refresh');
            }
        }
        
        //Calendar events function.
        function calendar($param1 = '', $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
            if($_GET['id'] != "")
            {
                $notify['status'] = 1;
                $this->db->where('id', $_GET['id']);
                $this->db->update('notification', $notify);
            }
            if($param1 == 'create')
            {
                $this->crud->createCalendarEvent();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/calendar/', 'refresh');
            }
            if($param1 == 'update'){
                $this->crud->updateCalendarEvent();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/calendar/', 'refresh');
            }
            if($param1 == 'update_date')
            {
                $this->crud->updateCalendarDate();
            }
            $page_data['page_name']  = 'calendar';
            $page_data['page_title'] = getEduAppGTLang('calendar');
            $this->load->view('backend/index', $page_data); 
        }
    
        //Attendance report selector function.
        function attendance_report_selector()
        {
           if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $data['class_id']   = $this->input->post('class_id');
            $data['year']       = $this->input->post('year');
            $data['month']  = $this->input->post('month');
            redirect(base_url().'admin/report_attendance_view/'.$data['class_id'].'/'.$data['month'].'/'.$data['year'],'refresh');
        }
       
        //Manage student payments function.
        function students_payments($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'students_payments';
            $page_data['page_title'] = getEduAppGTLang('student_payments');
            $this->load->view('backend/index', $page_data); 
        }
        
        //Manage payments function.
        function payments($param1 = '' , $param2 = '' , $param3 = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'payments';
            $page_data['page_title'] = getEduAppGTLang('payments');
            $this->load->view('backend/index', $page_data); 
        }
    
        //Manage expenses function.
        function expense($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->crud->createExpense();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/expense', 'refresh');
            }
            if ($param1 == 'edit') 
            {
                $this->crud->updateExpense($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/expense', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteExpense($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/expense/', 'refresh');
            }
            $page_data['page_name']  = 'expense';
            $page_data['page_title'] = getEduAppGTLang('expense');
            $this->load->view('backend/index', $page_data); 
        }
    
        //Manage expense categoies function.
        function expense_category($param1 = '' , $param2 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->crud->createCategory();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/expense');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateCategory($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/expense');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteCategory($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/expense');
            }
            $page_data['page_name']  = 'expense';
            $page_data['page_title'] = getEduAppGTLang('expense');
            $this->load->view('backend/index', $page_data);
        }
    
        //Teacher attendance function.
        function teacher_attendance()
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['page_name']  =  'teacher_attendance';
            $page_data['page_title'] =  getEduAppGTLang('teacher_attendance');
            $this->load->view('backend/index', $page_data);
        }
    
        //Teacher attendance report function.
        function teacher_attendance_report() 
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
             $page_data['month']        =  date('m');
             $page_data['page_name']    = 'teacher_attendance_report';
             $page_data['page_title']   = getEduAppGTLang('teacher_attendance_report');
             $this->load->view('backend/index',$page_data);
         }
    
        //Teacher report selector function.
        function teacher_report_selector()
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $data['year']       = $this->input->post('year');
            $data['month']      = $this->input->post('month');
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
            redirect(base_url().'admin/teacher_report_view/'.$data['month'].'/'.$data['year'],'refresh');
        }

        //Teachers report view function.
        function teacher_report_view($month = '', $year = '') 
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['month']      = $month;
            $page_data['year']       = $year;
            $page_data['page_name']  = 'teacher_report_view';
            $page_data['page_title'] = getEduAppGTLang('teacher_attendance_report');
            $this->load->view('backend/index', $page_data);
         }
    
        //Attendance for teachers function.
        function attendance_teacher()
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $timestamp = $this->crud->teacherAttendance();
            redirect(base_url().'admin/teacher_attendance_view/'. $timestamp,'refresh');
        }
    
        //Update attendance for teachers function.
        function attendance_update2($timestamp = '')
        {
            if ($this->session->userdata('admin_login') != 1) 
            {
                $this->session->set_userdata('last_page', current_url());
                redirect(base_url(), 'refresh');
            }
            $this->crud->updateAttendance($timestamp);
            $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
            redirect(base_url().'admin/teacher_attendance_view/'.$timestamp , 'refresh');
        }
    
        //View teacher attendance function.
        function teacher_attendance_view($timestamp = '')
        {
            if($this->session->userdata('admin_login')!=1)
            {
                redirect(base_url() , 'refresh');
            }
            $page_data['timestamp'] = $timestamp;
            $page_data['page_name'] = 'teacher_attendance_view';
            $page_data['page_title'] = getEduAppGTLang('teacher_attendance');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage school bus function.
        function school_bus($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->crud->createBus();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/school_bus/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateBus($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/school_bus', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteBus($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/school_bus/', 'refresh');
            }
            $page_data['page_name']  = 'school_bus';
            $page_data['page_title'] = getEduAppGTLang('school_bus');
            $this->load->view('backend/index', $page_data); 
        }
        
        //Manage your classrooms function.
        function classrooms($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
    
               redirect(base_url(), 'refresh');
            }
            if ($param1 == 'create') 
            {
                $this->crud->createClassroom();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_added'));
                redirect(base_url() . 'admin/classrooms/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateClassroom($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/classrooms/', 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteClassroom($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/classrooms/', 'refresh');
            }
            $page_data['page_name']   = 'classroom';
            $page_data['page_title']  = getEduAppGTLang('classrooms');
            $this->load->view('backend/index', $page_data);
        }
    
        //Update social login function.
        function social($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if($param1 == 'login')
            {   
                $this->crud->updateSocialLogin();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/system_settings/', 'refresh');
            }
        }
    
        //System settings function.
        function system_settings($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            if ($param1 == 'do_update') 
            {
                $this->crud->updateSettings();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/system_settings/', 'refresh');
            }
            if($param1 == 'skin')
            {
                $this->crud->updateSkins();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/system_settings/', 'refresh');
            }
            if($param1 == 'social')
            {
                $this->crud->updateSocial();
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_updated'));
                redirect(base_url() . 'admin/system_settings/', 'refresh');
            }
            $page_data['page_name']  = 'system_settings';
            $page_data['page_title'] = getEduAppGTLang('system_settings');
            $this->load->view('backend/index', $page_data);
        }
    
        //Classes functions.
        function grados($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['page_name']  = 'grados';
            $page_data['page_title'] = getEduAppGTLang('classes');
            $this->load->view('backend/index', $page_data);
        }
        
        //Subjects function.
        function cursos($class_id = '')
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $page_data['class_id']  = $class_id;
            $page_data['page_name']  = 'cursos';
            $page_data['page_title'] =  getEduAppGTLang('subjects');
            $this->load->view('backend/index', $page_data);
        }
    
        //Manage Library function.
        function library($param1 = '', $param2 = '', $param3 = '')
        {
            if ($this->session->userdata('admin_login') != 1){
                redirect(base_url(), 'refresh');
            } 
            if ($param1 == 'create') 
            {
                $this->crud->createBook();
                redirect(base_url() . 'admin/library/', 'refresh');
            }
            if ($param1 == 'update') 
            {
                $this->crud->updateBook($param2);
                redirect(base_url() . 'admin/update_book/'.$param2, 'refresh');
            }
            if ($param1 == 'delete') 
            {
                $this->crud->deleteBook($param2);
                $this->session->set_flashdata('flash_message' , getEduAppGTLang('successfully_deleted'));
                redirect(base_url() . 'admin/library', 'refresh');
            }
            $id = $this->input->post('class_id');
            if ($id == '')
            {
                $id = $this->db->get('class')->first_row()->class_id;
            }
            $page_data['id']         = $id;
            $page_data['page_name']  = 'library';
            $page_data['page_title'] = getEduAppGTLang('library');
            $this->load->view('backend/index', $page_data);
        }
    
        //Marks print view function.
        function marks_print_view($student_id  = '', $exam_id = '') 
        {
            if ($this->session->userdata('admin_login') != 1)
            {
                redirect(base_url(), 'refresh');
            }
            $class_id     = $this->db->get_where('enroll' , array('student_id' => $student_id , 'year' => $this->runningYear))->row()->class_id;
            $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
            $page_data['student_id'] =   $student_id;
            $page_data['class_id']   =   $class_id;
            $page_data['exam_id']    =   $exam_id;
            $this->load->view('backend/admin/marks_print_view', $page_data);
        }
        
        function marks_report_student()
        {
         $datea = $this->payment->marks_report_student();
          echo  json_encode($datea) ;
        }
        
        //End of Admin.php content.
    }