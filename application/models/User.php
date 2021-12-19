<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends School 
{
    private $runningYear = '';
    
    function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->runningYear = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $this->load->library('excel');
        $this->load->library('pdf');
    }
    
    public function getAccountantInfo()
    {
        $info = $this->db->get_where('accountant', array('accountant_id' => $this->session->userdata('login_user_id')))->result_array();
        return $info;
    }
    
    public function checkPublicEmail()
    {
        if($_POST['c'] != "")
        {
            $credential = array('email' => $_POST['c']);
            $admin_query = $this->db->get_where('admin', $credential);
            if ($admin_query->num_rows() > 0) 
            {
                return 'success';
            }
            $teacher_query = $this->db->get_where('teacher', $credential);
            if ($teacher_query->num_rows() > 0) 
            {
              return 'success';
            }             
            $student_query = $this->db->get_where('student', $credential);
            if ($student_query->num_rows() > 0) 
            {
                return 'success';
            }
            $parent_query = $this->db->get_where('parent', $credential);
            if ($parent_query->num_rows() > 0) 
            {
                return 'success';                  
            } 
            $accountant_query = $this->db->get_where('accountant', $credential);
            if ($accountant_query->num_rows() > 0) 
            {
                return 'success';                  
            } 
            $librarian_query = $this->db->get_where('librarian', $credential);
            if ($librarian_query->num_rows() > 0) 
            {
                return 'success';                  
            } 
        }
    }
    
    public function createLibrarian()
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['address']      = $this->input->post('address');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['phone']        = $this->input->post('phone');
        $data['gender']       = $this->input->post('gender');
        $data['birthday']     = $this->input->post('datetimepicker');
        $data['email']        = $this->input->post('email');
        $data['since']        = $this->crud->getDateFormat();
        $data['username']     = $this->input->post('username');
        $data['password']     = sha1($this->input->post('password'));
        $data['doj']   = $this->input->post('doj');
        $data['aadhar']         = $this->input->post('aadhar');
        $data['salary']         = $this->input->post('salary');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['cheque_photo']        = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        $data['pan_photo']        = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        $data['aadhar_photo']        = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        $this->db->insert('librarian', $data);
        $librarian_id = $this->db->insert_id();
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
    }
    
    public function updateLibrarian($librarianId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['address']      = $this->input->post('address');
        $data['phone']        = $this->input->post('phone');
        $data['gender']       = $this->input->post('gender');
        $data['birthday']     = $this->input->post('datetimepicker');
        $data['email']        = $this->input->post('email');
        $data['since']        = $this->crud->getDateFormat();
        $data['username']     = $this->input->post('username');
        $data['password']     = sha1($this->input->post('password'));
        $data['salary']         = $this->input->post('salary');
        $data['aadhar']         = $this->input->post('aadhar');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        if($this->input->post('doj') != ''){
            $data['doj']   = date("d-m-Y", strtotime($this->input->post('doj')));   
        }
        if($this->input->post('dol') != ''){
            $data['dol']   = date("d-m-Y", strtotime($this->input->post('dol')));   
        }        
        if($this->input->post('datetimepicker') != ''){
            $data['birthday'] = $this->input->post('datetimepicker');   
        }
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        if($_FILES['userfile']['name'] != ""){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($_FILES['aadhar_photo']['name'] != ""){
            $data['aadhar_photo']    = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if($_FILES['pan_photo']['name'] != ""){
            $data['pan_photo']    = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if($_FILES['cheque_photo']['name'] != ""){
            $data['cheque_photo']    = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }

        $this->db->where('librarian_id', $librarianId);
        $this->db->update('librarian', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
    }
    
    public function deleteLibrarian($librarianId)
    {
        $this->db->where('librarian_id', $librarianId);
        $this->db->delete('librarian');
    }
    
    public function createAccountant()
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['address']      = $this->input->post('address');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['phone']        = $this->input->post('phone');
        $data['gender']       = $this->input->post('gender');
        $data['birthday']     = $this->input->post('datetimepicker');
        $data['email']        = $this->input->post('email');
        $data['since']        = $this->crud->getDateFormat();
        $data['username']     = $this->input->post('username');
        $data['password']     = sha1($this->input->post('password'));
        $data['doj']   = $this->input->post('doj');
        $data['aadhar']         = $this->input->post('aadhar');
        $data['salary']         = $this->input->post('salary');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['cheque_photo']        = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        $data['pan_photo']        = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        $data['aadhar_photo']        = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        $this->db->insert('accountant', $data);
        $accountant_id = $this->db->insert_id();
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));        
    }
    
    public function updateAccountant($accountantId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['address']      = $this->input->post('address');
        $data['phone']        = $this->input->post('phone');
        $data['gender']       = $this->input->post('gender');
        $data['birthday']     = $this->input->post('datetimepicker');
        $data['email']        = $this->input->post('email');
        $data['since']        = $this->crud->getDateFormat();
        $data['username']     = $this->input->post('username');
        $data['password']     = sha1($this->input->post('password'));
        $data['salary']         = $this->input->post('salary');
        $data['aadhar']         = $this->input->post('aadhar');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        if($this->input->post('doj') != ''){
            $data['doj']   = $this->input->post('doj');   
        }
        if($this->input->post('dol') != ''){
            $data['dol']   = $this->input->post('dol');   
        }        
        if($this->input->post('datetimepicker') != ''){
            $data['birthday'] = $this->input->post('datetimepicker');   
        }
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        if($_FILES['userfile']['name'] != ""){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($_FILES['aadhar_photo']['name'] != ""){
            $data['aadhar_photo']    = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if($_FILES['pan_photo']['name'] != ""){
            $data['pan_photo']    = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if($_FILES['cheque_photo']['name'] != ""){
            $data['cheque_photo']    = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }
        $this->db->where('accountant_id', $accountantId);
        $this->db->update('accountant', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
    }
    
    public function deleteAccountant($accountantId)
    {
        $this->db->where('accountant_id', $accountantId);
        $this->db->delete('accountant');
    }
    
    public function createOfficeStaff()
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['gender']   = $this->input->post('gender');
        $data['phone']   = $this->input->post('phone');
        $data['designation']   = $this->input->post('designation');
        $data['address']   = $this->input->post('address');
        $data['salary']   = $this->input->post('salary');
        $data['since']   = $this->input->post('since');
        $data['birthday']   = $this->input->post('birthday');
        $data['doj']   = $this->input->post('doj');
        $data['aadhar']   = $this->input->post('aadhar');
        $data['pan']   = $this->input->post('pan');
        $data['bank_no']   = $this->input->post('bank_no');
        $data['bank_name']   = $this->input->post('bank_name');
        $data['bank_ifsc']   = $this->input->post('bank_ifsc');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['aadhar_photo']      = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        $data['pan_photo']      = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        $data['cheque_photo']      = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        $this->db->insert('officestaff', $data);
        $teacher_id = $this->db->insert_id();
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
    }
    public function updateOfficeStaff($stafftId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['gender']   = $this->input->post('gender');
        $data['phone']   = $this->input->post('phone');
        $data['designation']   = $this->input->post('designation');
        $data['address']   = $this->input->post('address');
        $data['salary']   = $this->input->post('salary');
        $data['since']   = $this->input->post('since');
        $data['doj']   = $this->input->post('doj');
        $data['aadhar']   = $this->input->post('aadhar');
        $data['pan']   = $this->input->post('pan');
        $data['bank_no']   = $this->input->post('bank_no');
        $data['bank_name']   = $this->input->post('bank_name');
        $data['bank_ifsc']   = $this->input->post('bank_ifsc');
        if($this->input->post('doj') != ''){
            $data['doj']   = $this->input->post('doj');   
        }
        if($this->input->post('dol') != ''){
            $data['dol']   = $this->input->post('dol');   
        }
        if($this->input->post('datetimepicker') != ''){
            $data['birthday'] = $this->input->post('datetimepicker');   
        }
        if($_FILES['userfile']['name'] != ""){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($_FILES['aadhar_photo']['name'] != ""){
            $data['aadhar_photo']    = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if($_FILES['pan_photo']['name'] != ""){
            $data['pan_photo']    = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if($_FILES['cheque_photo']['name'] != ""){
            $data['cheque_photo']    = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }
        $this->db->where('staff_id', $stafftId);
        $this->db->update('officestaff', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/officestaff_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));        
    }
    
    public function deleteOfficeStaff($staffId)
    {
        $this->db->where('staff_id', $staffId);
        $this->db->delete('officestaff');
    }

    public function createAdmin()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        $data['designation']  = $this->input->post('designation');
        $data['username']     = $this->input->post('username');
        $data['password']     = sha1($this->input->post('password'));
        $data['email']        = $this->input->post('email');
        $data['birthday']     = $this->input->post('datetimepicker');
        if($this->input->post('doj') == "")
            $data['doj']        = $this->crud->getDateFormat();
        else
            $data['doj']          = $this->input->post('doj');
        $data['gender']       = $this->input->post('gender');
        $data['phone']        = $this->input->post('phone');
        $data['image']        = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $data['address']      = $this->input->post('address');
        $data['since']        = $this->crud->getDateFormat();
        $data['aadhar']         = $this->input->post('aadhar');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        $data['cheque_photo']        = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        $data['pan_photo']        = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        $data['aadhar_photo']        = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        $data['owner_status'] = $this->input->post('owner_status');
        $this->db->insert('admin', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
    }
    
    public function updateAdmin($adminId)
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['first_name']   = $this->input->post('first_name');
        if($this->input->post('designation') != ""){
            $data['birthday'] = $this->input->post('designation');   
        }
        $data['username']     = $this->input->post('username');
        $data['email']        = $this->input->post('email');
        if($this->input->post('datetimepicker') != ""){
            $data['birthday'] = $this->input->post('datetimepicker');   
        }
        $data['gender']       = $this->input->post('gender');
        $data['phone']        = $this->input->post('phone');
        $data['address']      = $this->input->post('address');
        $data['aadhar']         = $this->input->post('aadhar');
        $data['pan']            = $this->input->post('pan');
        $data['bank_no']        = $this->input->post('bank_no');
        $data['bank_name']      = $this->input->post('bank_name');
        $data['bank_ifsc']      = $this->input->post('bank_ifsc');
        $data['doj']            = $this->input->post('doj');
        if($this->input->post('dol') != ""){
            $data['dol']      = $this->input->post('dol');   
        }
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        if($_FILES['userfile']['size'] > 0){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($_FILES['aadhar_photo']['size'] > 0){
            $data['aadhar_photo']    = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if($_FILES['pan_photo']['size'] > 0){
            $data['pan_photo']    = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if($_FILES['cheque_photo']['size'] > 0){
            $data['cheque_photo']    = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }    
        if($this->input->post('datetimepicker') != ""){
            $data['birthday']     = $this->input->post('datetimepicker');   
        }
        if($this->input->post('profession') != ""){
            $data['profession']     = $this->input->post('profession');   
        }
        if($this->input->post('idcard') != ""){
            $data['idcard']     = $this->input->post('idcard');   
        }
        $data['owner_status'] = $this->input->post('owner_status');
        $this->db->where('admin_id', $adminId);
        $this->db->update('admin', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/admin_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
    }
    
    public function acceptTeacher($teacherId)
    {
        $pending = $this->db->get_where('pending_users', array('user_id' => $teacherId))->result_array();
        foreach ($pending as $row) 
        {
            $data['first_name'] = $row['first_name'];
            $data['email']      = $row['email'];
            $data['username']   = $row['username'];
            $data['sex']        = $row['sex'];
            $data['password']   = $row['password'];
            $data['phone']      = $row['phone'];
            $data['since']      = $row['since'];
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            $this->mail->accountConfirm('teacher', $teacher_id);
        }
        $this->db->where('user_id', $teacherId);
        $this->db->delete('pending_users');
    }
    
    public function createTeacher()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['first_name']  = $this->input->post('first_name');
        $designationPriority = $this->input->post('designation');
        $x = explode(",",$designationPriority);//Storing Priority of the Designation also.
        $data['designation'] = $x[0];
        $data['designation_priority'] = $x[1];
        $data['sex']         = $this->input->post('gender');
        $data['email']       = $this->input->post('email');
        $data['phone']       = $this->input->post('phone');
        $data['idcard']      = $this->input->post('idcard');
        $data['since']       = $this->crud->getDateFormat();
        $data['birthday']    = $this->input->post('datetimepicker');
        $data['address']     = $this->input->post('address');
        $data['username']    = $this->input->post('username');
        $data['department']    = $this->input->post('department');
        $data['teaching_subject']    = $this->input->post('teaching_subject');
        $data['other_subject']    = $this->input->post('other_subject');
        $data['doj']    = $this->input->post('doj');
        $data['qualification']    = $this->input->post('qualification');
        $data['exp']    = $this->input->post('exp');
        $data['aadhar']    = $this->input->post('aadhar');
        $data['pan']    = $this->input->post('pan');
        $data['bank_no']    = $this->input->post('bank_no');
        $data['bank_name']    = $this->input->post('bank_name');
        $data['bank_ifsc']    = $this->input->post('bank_ifsc');
        $data['aadhar_photo']    = $this->input->post('aadhar_photo');
        $data['pan_photo']    = $this->input->post('pan_photo');
        $data['cheque_photo']    = $this->input->post('cheque_photo');
        $data['salary']    = $this->input->post('salary');
        if(!empty($_FILES['userfile']['tmp_name'])){
            $data['image']   = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if(!empty($_FILES['aadhar_photo']['tmp_name'])){
            $data['aadhar_photo']   = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if(!empty($_FILES['pan_photo']['tmp_name'])){
            $data['pan_photo']   = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if(!empty($_FILES['cheque_photo']['tmp_name'])){
            $data['cheque_photo']   = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }
        $data['password']    = sha1($this->input->post('password'));
        $this->db->insert('teacher', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function updateTeacher($teacherId)
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['first_name']  = $this->input->post('first_name');
        $data['designation']   = $this->input->post('designation');
        $data['sex']         = $this->input->post('gender');
        $data['email']       = $this->input->post('email');
        $data['phone']       = $this->input->post('phone');
        $data['idcard']      = $this->input->post('idcard');
        $data['since']       = $this->crud->getDateFormat();
        $data['address']     = $this->input->post('address');
        $data['username']    = $this->input->post('username');
        $data['department']    = $this->input->post('department');
        $data['teaching_subject']    = $this->input->post('teaching_subject');
        $data['other_subject']    = $this->input->post('other_subject');        
        $data['doj']    = $this->input->post('doj');
        $data['qualification']    = $this->input->post('qualification');
        $data['exp']    = $this->input->post('exp');
        $data['aadhar']    = $this->input->post('aadhar');
        $data['pan']    = $this->input->post('pan');
        $data['bank_no']    = $this->input->post('bank_no');
        $data['bank_name']    = $this->input->post('bank_name');
        $data['bank_ifsc']    = $this->input->post('bank_ifsc');
        $data['aadhar_photo']    = $this->input->post('aadhar_photo');
        $data['pan_photo']    = $this->input->post('pan_photo');
        $data['cheque_photo']    = $this->input->post('cheque_photo');
        $data['salary']    = $this->input->post('salary');
        if($this->input->post('dol') != ''){
            $data['dol']   = $this->input->post('dol');   
        }
        if($this->input->post('datetimepicker') != ''){
            $data['birthday'] = $this->input->post('datetimepicker');
        }
        if(!empty($_FILES['userfile']['tmp_name'])){
            $data['image']   = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if(!empty($_FILES['aadhar_photo']['tmp_name'])){
            $data['aadhar_photo']   = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);
        }
        if(!empty($_FILES['pan_photo']['tmp_name'])){
            $data['pan_photo']   = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);
        }
        if(!empty($_FILES['cheque_photo']['tmp_name'])){
            $data['cheque_photo']   = $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']);
        }        
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        $this->db->where('teacher_id', $teacherId);
        $this->db->update('teacher', $data);
        if(!empty($_FILES['userfile']['tmp_name'])){
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['image']['name']));
        }
        if(!empty($_FILES['aadhar_photo']['tmp_name'])){
            move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        }
        if(!empty($_FILES['pan_photo']['tmp_name'])){
            move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        }
        if(!empty($_FILES['cheque_photo']['tmp_name'])){
            move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['cheque_photo']['name']));
        }
    }
    
    public function deleteTeacher($teacherId)
    {
        $this->db->where('teacher_id', $teacherId);
        $this->db->delete('teacher');
    }
    
    public function createParent()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['first_name']     = $this->input->post('first_name');
        $data['gender']         = "M";
        $data['profession']     = $this->input->post('profession');
        $data['email']          = $this->input->post('email');
        $data['phone']          = $this->input->post('phone');
        $data['home_phone']     = $this->input->post('home_phone');
        $data['since']          = $this->crud->getDateFormat();
        $data['idcard']         = $this->input->post('idcard');
        $data['business']       = $this->input->post('business');
        $data['business_phone'] = $this->input->post('business_phone');
        $data['address']        = $this->input->post('address');
        $data['username']       = $this->input->post('username');
        $data['password']       = sha1($this->input->post('password'));
        $data['image']          = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        $this->db->insert('parent', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/parent_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function updateParent($parentId)
    {
        if(!empty($_FILES['userfile']['tmp_name'])){
            $data['image']     = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        $data['first_name']      = $this->input->post('first_name');
        $data['gender']          = "M";
        $data['profession']      = $this->input->post('profession');
        $data['email']           = $this->input->post('email');
        $data['phone']           = $this->input->post('phone');
        $data['home_phone']      = $this->input->post('home_phone');
        $data['idcard']          = $this->input->post('idcard');
        $data['business']        = $this->input->post('business');
        $data['business_phone']  = $this->input->post('business_phone');
        $data['address']         = $this->input->post('address');
        if($this->input->post('username') != ''){
            $data['username']        = $this->input->post('username');   
        }
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));
        }
        $this->db->where('parent_id' , $parentId);
        $this->db->update('parent' , $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/parent_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    //Create a Parent from Student Profile Page by the SuperAdmin user only
    public function create_student_parent($studentId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));    
        $parent_data['first_name']      = $this->input->post('parent_first_name');
        $parent_data['profession']      = $this->input->post('parent_profession');
        $parent_data['email']           = $this->input->post('parent_email');
        $parent_data['phone']           = $this->input->post('parent_phone');
        $parent_data['last_name']       = $this->input->post('parent_last_name');
        $parent_data['idcard']          = $this->input->post('parent_idcard');
        $parent_data['home_phone']      = $this->input->post('parent_home_phone');
        $parent_data['parent_mother_email']      = $this->input->post('parent_mother_email');
        $parent_data['address']         = $this->input->post('parent_address');
        $parent_data['username']        = $this->input->post('parent_username');
        $parent_data['password']        = sha1($this->input->post('parent_password'));
        $parent_data['since']           = $this->crud->getDateFormat();
        $this->db->insert('parent', $parent_data);
        $parent_id = $this->db->insert_id();
        $data['parent_id']        = $parent_id;    
        $this->db->where('student_id', $studentId);
        $this->db->update('student', $data);
    }
    //Update a Parent from Student Profile Page by the SuperAdmin user only
    public function update_student_parent($parent_id)
    {
        $md5 = md5(date('d-m-Y H:i:s'));    
        $parent_data['first_name']      = $this->input->post('parent_first_name');
        $parent_data['profession']      = $this->input->post('parent_profession');
        $parent_data['email']           = $this->input->post('parent_email');
        $parent_data['phone']           = $this->input->post('parent_phone');
        $parent_data['last_name']       = $this->input->post('parent_last_name');
        $parent_data['idcard']          = $this->input->post('parent_idcard');
        $parent_data['home_phone']      = $this->input->post('parent_home_phone');
        $parent_data['parent_mother_email']      = $this->input->post('parent_mother_email');
        $parent_data['address']         = $this->input->post('parent_address');
        $parent_data['username']        = $this->input->post('parent_username');
        $parent_data['password']        = sha1($this->input->post('parent_password'));
        $parent_data['since']           = $this->crud->getDateFormat();
        $this->db->where('parent_id', $parent_id);
        $this->db->update('parent', $parent_data);
    }
    //Update a MISC from Student Profile Page by the SuperAdmin user only
    public function student_misc_update($studentId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));    
        $data['diseases']          = $this->input->post('diseases');
        $data['allergies']         = $this->input->post('allergies');
        $data['doctor']            = $this->input->post('doctor');
        $data['doctor_phone']      = $this->input->post('doctor_phone');
        $data['authorized_person'] = $this->input->post('auth_person');
        $data['authorized_phone']  = $this->input->post('auth_phone');
        $data['note']              = $this->input->post('note');
        $this->db->where('student_id', $studentId);
        $this->db->update('student', $data);
    }

    public function acceptParent($parentId)
    {
        $pending = $this->db->get_where('pending_users', array('user_id' => $parentId))->result_array();
        foreach ($pending as $row) 
        {
            $data['first_name']  = $row['first_name'];
            $data['email']       = $row['email'];
            $data['sex']       = "M";
            $data['username']    = $row['username'];
            $data['profession']  = $row['profession'];
            $data['since']       = $row['since'];
            $data['password']    = $row['password'];
            $data['phone']       = $row['phone'];
            $this->db->insert('parent', $data);
            $parent_id = $this->db->insert_id();
            $this->mail->accountConfirm('parent', $parent_id);
        }
        $this->db->where('user_id', $parentId);
        $this->db->delete('pending_users');   
    }
    
    public function deleteParent($parentId)
    {
        $this->db->where('parent_id' , $parentId);
        $this->db->delete('parent');
    }
    
    public function updateCurrentAdmin()
    {
        $data['first_name']   = $this->input->post('first_name');
        $data['username']     = $this->input->post('username');
        $data['email']        = $this->input->post('email');
        $data['profession']   = $this->input->post('profession');
        $data['idcard']       = $this->input->post('idcard');
        if($this->input->post('datetimepicker') != ""){
            $data['birthday'] = $this->input->post('datetimepicker');   
        }
        if(!empty($_FILES['userfile']['tmp_name'])){
            $data['image']    = md5(date('d-m-y H:i:s')).str_replace(' ', '', $_FILES['userfile']['name']);
        }
        $data['gender']       = $this->input->post('gender');
        $data['phone']        = $this->input->post('phone');
        $data['address']      = $this->input->post('address');
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        $this->db->where('admin_id', $this->session->userdata('login_user_id'));
        $this->db->update('admin', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/admin_image/' . md5(date('d-m-y H:i:s')).str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function removeGoogle()
    {
        $data['g_oauth'] = "";
        $data['g_fname'] = "";
        $data['g_lname'] = "";
        $data['g_picture'] = "";
        $data['link'] = "";
        $data['g_email'] = "";  
        $this->db->where($this->session->userdata('login_type').'_id', $this->session->userdata('login_user_id'));
        $this->db->update($this->session->userdata('login_type'), $data);
        unset($_SESSION['token']);
        unset($_SESSION['userData']);
        $this->crud->googleRevokeToken();
    }
    
    public function removeFacebook()
    {
        $data['fb_token']   =  "";
        $data['fb_id']      =  "";
        $data['fb_photo']   =  "";
        $data['fb_name']    =  "";
        $data['femail']     = "";
        unset($_SESSION['access_token']);
        unset($_SESSION['userData']);
        $this->db->where($this->session->userdata('login_type').'_id', $this->session->userdata('login_user_id'));
        $this->db->update($this->session->userdata('login_type'), $data);
    }
    
    public function rejectStudent($studentId)
    {
        $this->db->where('user_id', $studentId);
        $this->db->delete('pending_users');
    }
    
    public function studentAdmission()
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']        = $this->input->post('first_name');
        $data['birthday']          = $this->input->post('datetimepicker');
        $data['username']          = $this->input->post('username');
        $data['student_session']   = 1;
        $data['email']             = $this->input->post('email');
        $data['since']             = $this->crud->getDateFormat();
        $data['phone']             = $this->input->post('phone');
        $data['sex']               = "M";
        $data['password']          = sha1($this->input->post('password'));
        $data['address']           = $this->input->post('address');
        $data['transport_id']      = $this->input->post('transport_id');
        $data['dormitory_id']      = $this->input->post('dormitory_id');
        $data['course']      = $this->input->post('course');
        $data['stdyear']      = $this->input->post('stdyear');
        $data['combination']      = $this->input->post('class_id');
        $data['icourse']      = $this->input->post('icourse');
        $data['language']      = $this->input->post('language');
        $data['hostel']      = $this->input->post('hostel');
        $data['caste']      = $this->input->post('caste');
        $data['caste_category']      = $this->input->post('caste_category');
        $data['religion']      = $this->input->post('religion');
        if($_FILES['userfile']['name'] != ''){
            $data['image']             = $md5.str_replace(' ', '', $_FILES['userfile']['name']);   
        }
        if($_FILES['aadhar_photo']['name'] != ''){
            $data['aadhar_photo']             = $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']);   
        }
        if($_FILES['pan_photo']['name'] != ''){
            $data['pan_photo']             = $md5.str_replace(' ', '', $_FILES['pan_photo']['name']);   
        }
        if($_FILES['school_certificate']['name'] != ''){
            $data['school_certificate']             = $md5.str_replace(' ', '', $_FILES['school_certificate']['name']);   
        }    
        if($this->input->post('account') != '1')
        {
            $data['parent_id']        = $this->input->post('parent_id');    
        }else if($this->input->post('account') == '1'){
            $data3['first_name']      = $this->input->post('parent_first_name');
            $data3['gender']          = "M";
            $data3['profession']      = $this->input->post('parent_profession');
            $data3['email']           = $this->input->post('parent_email');
            $data3['phone']           = $this->input->post('parent_phone');
            $data3['last_name']           = $this->input->post('parent_last_name');
            $data3['idcard']          = $this->input->post('parent_idcard');
            $data3['home_phone']      = $this->input->post('parent_home_phone');
            $data3['parent_mother_email']      = $this->input->post('parent_mother_email');
            $data3['address']         = $this->input->post('parent_address');
            $data3['username']        = $this->input->post('parent_username');
            $data3['password']        = sha1($this->input->post('parent_password'));
            $data3['since']           = $this->crud->getDateFormat();
            $data3['image']           = "";
            $this->db->insert('parent', $data3);
            $parent_id = $this->db->insert_id();
            $data['parent_id']        = $parent_id;    
        }
        $data['diseases']          = $this->input->post('diseases');
        $data['allergies']         = $this->input->post('allergies');
        $data['doctor']            = $this->input->post('doctor');
        $data['doctor_phone']      = $this->input->post('doctor_phone');
        $data['authorized_person'] = $this->input->post('auth_person');
        $data['authorized_phone']  = $this->input->post('auth_phone');
        $data['note']              = $this->input->post('note');
        $this->db->insert('student', $data);
        $student_id = $this->db->insert_id();
        $data4['student_id']       = $student_id;
        $data4['enroll_code']      = substr(md5(rand(0, 1000000)), 0, 7);
        $data4['class_id']         = $this->input->post('class_id');
        if ($this->input->post('section_id') != '') 
        {
            $data4['section_id']   = $this->input->post('section_id');
        }
        else {$data4['section_id'] = "A";}
        $data4['roll']             = $this->input->post('roll');
        $data4['date_added']       = strtotime(date("Y-m-d H:i:s"));
        $data4['year']             = $this->runningYear;
        $this->db->insert('enroll', $data4);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
        move_uploaded_file($_FILES['aadhar_photo']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['aadhar_photo']['name']));
        move_uploaded_file($_FILES['pan_photo']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['pan_photo']['name']));
        move_uploaded_file($_FILES['school_certificate']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['school_certificate']['name']));
        return $student_id;
    }
    
    public function acceptStudent($studentId)
    {
        $pending = $this->db->get_where('pending_users', array('user_id' => $studentId))->result_array();
        foreach ($pending as $row) 
        {
            $data['first_name']    = $row['first_name'];
            $data['email']         = $row['email'];
            $data['username']      = $row['username'];
            $data['sex']           = $row['sex'];
            $data['password']      = $row['password'];
            $data['birthday']      = $row['birthday'];
            $data['phone']         = $row['phone'];
            $data['since']         = $row['since'];
            $data['date']          = $this->crud->getDateFormat();
            $this->db->insert('student', $data);
            $student_id            = $this->db->insert_id();

            $data2['student_id']   = $student_id;
            $data2['enroll_code']  = substr(md5(rand(0, 1000000)), 0, 7);
            $data2['class_id']     = $row['class_id'];
            $data2['section_id']   = $row['section_id'];
            $data2['roll']         = $row['roll'];
            $data2['date_added']   = strtotime(date("Y-m-d H:i:s"));
            $data2['year']         = $this->runningYear;
            $this->db->insert('enroll', $data2);
            $this->mail->accountConfirm('student', $student_id);
        }
        $this->db->where('user_id', $studentId);
        $this->db->delete('pending_users');
    }
    
    public function bulkStudents()
    {
        $path   = $_FILES["upload_student"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        foreach($object->getWorksheetIterator() as $worksheet)
        {
           $highestRow = $worksheet->getHighestRow();
           $highestColumn = $worksheet->getHighestColumn();
           for($row=2; $row <= $highestRow; $row++)
           {                     
                $data['first_name']    =  $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $data['email']         =  $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $data['phone']         =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $data['sex']           =  $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $data['username']      =  $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $data['password']      =  sha1($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $data['address']       =  $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $data['course']        =  "PU";
                $data['stdyear']       =  $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $data['since']         =  $this->crud->getDateFormat();
                if($data['first_name'] != "")
                {
                    $this->db->insert('student',$data);
                    $student_id = $this->db->insert_id();
                    $data2['enroll_code']   =   substr(md5(rand(0, 1000000)), 0, 7);
                    $data2['student_id']    =   $student_id;
                    $data2['class_id']      =   $this->input->post('class_id');
                    if($this->input->post('section_id') != '') 
                    {
                        $data2['section_id']    =   $this->input->post('section_id');
                    }
                    $data2['roll']          =   $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $data2['date_added']    =   strtotime(date("Y-m-d H:i:s"));
                    $data2['year']          =   $this->runningYear;
                    $this->db->insert('enroll' , $data2);
                }
           }
        }
    }
    
    public function updateStudent($studentId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']        = $this->input->post('first_name');
        $data['birthday']          = $this->input->post('datetimepicker');
        $data['username']          = $this->input->post('username');
        $data['student_session']   = 1;
        $data['email']             = $this->input->post('email');
        $data['since']             = $this->crud->getDateFormat();
        $data['phone']             = $this->input->post('phone');
        if($this->input->post('password') != "")
        {
           $data['password'] = sha1($this->input->post('password'));
        }
        $data['address']           = $this->input->post('address');
        $data['sex']           = $this->input->post('gender');
        $data['transport_id']      = $this->input->post('transport_id');
        $data['course']      = $this->input->post('course');
        $data['stdyear']      = $this->input->post('stdyear');
        $data['combination']      = $this->input->post('class_id');
        $data['icourse']      = $this->input->post('icourse');
        $data['language']      = $this->input->post('language');
        $data['hostel']      = $this->input->post('hostel');
        $data['caste']      = $this->input->post('caste');
        $data['caste_category']      = $this->input->post('caste_category');
        $data['religion']      = $this->input->post('religion');
        $data['leave_reason']      = $this->input->post('leave_reason');
        $data['leave_reason_remarks']      = $this->input->post('leave_reason_remarks');
        $this->db->where('student_id', $studentId);
        $this->db->update('student', $data);
        $data2['roll']             = $this->input->post('roll');
        $data2['class_id']         = $this->input->post('class_id');
        $data2['section_id']       = $this->input->post('section_id');
        $this->db->where('student_id', $studentId);
        $this->db->update('enroll', $data2);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function updateCurrentStudent()
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['email'] = $this->input->post('email');
        $data['phone'] = $this->input->post('phone');
        $data['address'] = $this->input->post('address');
        if($this->input->post('password') != "")
        {
            $data['password'] = sha1($this->input->post('password'));
        }
        if($_FILES['userfile']['size'] > 0){
            $data['image']     = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        $this->db->where('student_id', $this->session->userdata('login_user_id'));
        $this->db->update('student', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function updateModalStudent($studentId)
    {
        $md5 = md5(date('d-m-Y H:i:s'));
        $data['first_name']            = $this->input->post('first_name');
        $data['username']        = $this->input->post('username');
        $data['phone']           = $this->input->post('phone');
        $data['address']         = $this->input->post('address');
        $data['parent_id']       = $this->input->post('parent_id');
        $data['student_session'] = $this->input->post('student_session');
        $data['email']           = $this->input->post('email');
        if($_FILES['userfile']['size'] > 0){
            $data['image']     = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($this->input->post('password') != "")
        {
           $data['password'] = sha1($this->input->post('password'));
        }
        $this->db->where('student_id', $studentId);
        $this->db->update('student', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/student_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function downloadExcel()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', getEduAppGTLang('first_name'));
        $objPHPExcel->getActiveSheet()->setCellValue('B1', getEduAppGTLang('username'));
        $objPHPExcel->getActiveSheet()->setCellValue('C1', getEduAppGTLang('email'));
        $objPHPExcel->getActiveSheet()->setCellValue('D1', getEduAppGTLang('phone'));
        $objPHPExcel->getActiveSheet()->setCellValue('E1', getEduAppGTLang('gender'));
        $objPHPExcel->getActiveSheet()->setCellValue('F1', getEduAppGTLang('class'));
        $objPHPExcel->getActiveSheet()->setCellValue('G1', getEduAppGTLang('section'));
        $objPHPExcel->getActiveSheet()->setCellValue('H1', getEduAppGTLang('parent'));

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f = 2;$g = 2;$h=2; $i = 2;

        $query = $this->db->get_where('enroll', array('class_id' => $this->input->post('class_id'), 'section_id' => $this->input->post('section_id'), 'year' => $this->runningYear))->result_array();
        foreach($query as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->first_name);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->username);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->email);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->phone);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->sex);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $this->db->get_where('section', array('section_id' => $row['section_id']))->row()->name);
            $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $this->crud->get_name('parent',$parent_id));
        }
        $objPHPExcel->getActiveSheet()->setTitle(getEduAppGTLang('students'));
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export_students_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadStudents()
    {
        if(isset($_POST['class_id']) && !empty($_POST['class_id']))
        {
            $myclass_id = implode(',',$_POST['class_id']);
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {
            $mystdyear = implode('','',$_POST['stdyear']);
        }
        
        $conmyclass_id = !empty($myclass_id) ? 'student.combination IN ('.$myclass_id.')' : '1=1';

        $conmymystdyear = !empty($mystdyear) ? 'student.stdyear IN ('.$mystdyear.')' : '1=1';
        
        $filename = "Students_".date('d-m-y:h:i:s').".csv";
        $fp  = fopen('php://output', 'w');
    
        $query = $this->db->query("SELECT 
        student.first_name as fullanme,
        student.email as semail, 
        student.phone as sphone,
        student.sex as gendar,
        student.pan_photo as pan,
        student.aadhar_photo as adhar,
        parent.first_name as fathername,
        parent.phone as fathermobile,
        parent.last_name as mothername,
        parent.home_phone as mothermobile,
        student.religion as religion,
        student.caste as caste,
        student.caste_category as castecate,
        student.hostel as hostel,
        student.transportation as transportation,
        student.icourse as course,
        enroll.year as joiningyear,
        class.name as scallss,
        student.stdyear as sstdyear
        FROM `student` 
        LEFT JOIN parent on student.parent_id = parent.parent_id 
        LEFT JOIN class on student.combination = class.class_id 
        LEFT JOIN enroll on student.student_id = enroll.student_id WHERE $conmyclass_id AND $conmymystdyear")->result_array(); 

        $arr2 = array();

        if(isset($_POST['sex']) && !empty($_POST['sex']))
        {
            array_push($arr2,'Gender');
        }
        if(isset($_POST['pan']) && !empty($_POST['pan']))
        {
            array_push($arr2,'PAN');
        }
        if(isset($_POST['aadhar']) && !empty($_POST['aadhar']))
        {
            array_push($arr2,'Aadhar');
        }
        if(isset($_POST['parent_first_name']) && !empty($_POST['parent_first_name']))
        {
            array_push($arr2,'Father Name');
        }
        if(isset($_POST['parent_phone']) && !empty($_POST['parent_phone']))
        {
            array_push($arr2,'Father Phone');
        }
        if(isset($_POST['parent_last_name']) && !empty($_POST['parent_last_name']))
        {
            array_push($arr2,'Mother Name');
        }
        if(isset($_POST['business_phone']) && !empty($_POST['business_phone']))
        {
            array_push($arr2,'Mother Phone');
        }
        if(isset($_POST['religion']) && !empty($_POST['religion']))
        {
            array_push($arr2,'Religion');
        }
        if(isset($_POST['caste']) && !empty($_POST['caste']))
        {
            array_push($arr2,'Caste');
        }

        if(isset($_POST['caste_category']) && !empty($_POST['caste_category']))
        {
            array_push($arr2,'Caste Category');
        }

        if(isset($_POST['hostel']) && !empty($_POST['hostel']))
        {
            array_push($arr2,'Hostel');
        }
        if(isset($_POST['transportation']) && !empty($_POST['transportation']))
        {
            array_push($arr2,'Transportation');
        }
        if(isset($_POST['icourse']) && !empty($_POST['icourse']))
        {
            array_push($arr2,'Course');
        }
        if(isset($_POST['year']) && !empty($_POST['year']))
        {
            array_push($arr2,'Academic Year');
        }
        if(isset($_POST['class_id']) && !empty($_POST['class_id']))
        {
            array_push($arr2,'Class');
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {
            array_push($arr2,'Course Year');
        }
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
        foreach($query as $k=>$row)
        {
            $rowData = array();
            foreach($row as $key=>$val)
            {
                if($key=='fullanme')
                {
                 array_push($rowData,$row[$key]);
                }
                if($key=='semail')
                {
                 array_push($rowData,$row[$key]);
                }
                if($key=='sphone')
                {
                 array_push($rowData,$row[$key]);
                }
                if(isset($_POST['sex']) && !empty($_POST['sex']))
                {
                    if($key=='gendar')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['pan']) && !empty($_POST['pan']))
                {
                    if($key=='pan')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['aadhar']) && !empty($_POST['aadhar']))
                {
                    if($key=='adhar')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['parent_first_name']) && !empty($_POST['parent_first_name']))
                {
                    if($key=='fathername')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['parent_phone']) && !empty($_POST['parent_phone']))
                {
                    if($key=='fathermobile')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['parent_last_name']) && !empty($_POST['parent_last_name']))
                {
                    if($key=='mothername')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['business_phone']) && !empty($_POST['business_phone']))
                    {
                    if($key=='mothermobile')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['religion']) && !empty($_POST['religion']))
                {
                    if($key=='religion')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['caste']) && !empty($_POST['caste']))
                    {
                    if($key=='caste')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['caste_category']) && !empty($_POST['caste_category']))
                {
                    if($key=='castecate')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['hostel']) && !empty($_POST['hostel']))
                {
                    if($key=='hostel')
                    {
                        if($row[$key]==1)
                        {
                            $hostaldata = 'Yes';
                        }
                        else
                        {
                            $hostaldata = 'No';
                        }
                        array_push($rowData,$hostaldata);
                    }
                }
                if(isset($_POST['transportation']) && !empty($_POST['transportation']))
                {
                    if($key=='transportation')
                    {
                        if($row[$key]==1)
                        {
                            $transportationdata = 'Yes';
                        }
                        else
                        {
                            $transportationdata = 'No';
                        }
                        array_push($rowData,$transportationdata);
                    }
                }
                if(isset($_POST['icourse']) && !empty($_POST['icourse']))
                {
                    if($key=='course')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['year']) && !empty($_POST['year']))
                {
                    if($key=='joiningyear')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['class_id']) && !empty($_POST['class_id']))
                {
                    if($key=='scallss')
                    {
                     array_push($rowData,$row[$key]);
                    }
                }
                if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
                {
                    if($key=='sstdyear')
                    {
                        if($row[$key]=='II')
                        {
                            $sstyed = 'PU-2';
                        }
                        else
                        {
                         $sstyed = 'PU-1';   
                        }
                     array_push($rowData,$sstyed);
                    }
                }
            }
            fputcsv($fp, $rowData);
            $i++;
        }
        //die;
        exit;
    }
    public function downloadPDFStudents()
    {
        if(isset($_POST['class_id']) && !empty($_POST['class_id']))
        {
            $myclass_id = implode(',',$_POST['class_id']);
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {
            $mystdyear = implode('','',$_POST['stdyear']);
        }
        $conmyclass_id = !empty($myclass_id) ? 'student.combination IN ('.$myclass_id.')' : '1=1';
        $conmymystdyear = !empty($mystdyear) ? 'student.stdyear IN ('.$mystdyear.')' : '1=1';
        
        $query = $this->db->query("SELECT 
        student.first_name as fullanme,
        student.email as semail, 
        student.phone as sphone,
        student.sex as gendar,
        student.pan_photo as pan,
        student.aadhar_photo as adhar,
        parent.first_name as fathername,
        parent.phone as fathermobile,
        parent.last_name as mothername,
        parent.home_phone as mothermobile,
        student.religion as religion,
        student.caste as caste,
        student.caste_category as castecate,
        student.hostel as hostel,
        student.transportation as transportation,
        student.icourse as course,
        enroll.year as joiningyear,
        class.name as scallss,
        student.stdyear as sstdyear
        FROM `student` 
        LEFT JOIN parent on student.parent_id = parent.parent_id 
        LEFT JOIN class on student.combination = class.class_id 
        LEFT JOIN enroll on student.student_id = enroll.student_id WHERE $conmyclass_id AND $conmymystdyear")->result_array(); 

        $arr2 = array();

        if(isset($_POST['sex']) && !empty($_POST['sex']))
        {
            array_push($arr2,'Gender');
        }
        if(isset($_POST['pan']) && !empty($_POST['pan']))
        {
            array_push($arr2,'PAN');
        }
        if(isset($_POST['aadhar']) && !empty($_POST['aadhar']))
        {
            array_push($arr2,'Aadhar');
        }
        if(isset($_POST['parent_first_name']) && !empty($_POST['parent_first_name']))
        {
            array_push($arr2,'Father Name');
        }
        if(isset($_POST['parent_phone']) && !empty($_POST['parent_phone']))
        {
            array_push($arr2,'Father Phone');
        }
        if(isset($_POST['parent_last_name']) && !empty($_POST['parent_last_name']))
        {
            array_push($arr2,'Mother Name');
        }
        if(isset($_POST['business_phone']) && !empty($_POST['business_phone']))
        {
            array_push($arr2,'Mother Phone');
        }
        if(isset($_POST['religion']) && !empty($_POST['religion']))
        {
            array_push($arr2,'Religion');
        }
        if(isset($_POST['caste']) && !empty($_POST['caste']))
        {
            array_push($arr2,'Caste');
        }
        if(isset($_POST['caste_category']) && !empty($_POST['caste_category']))
        {
            array_push($arr2,'Caste Category');
        }
        if(isset($_POST['hostel']) && !empty($_POST['hostel']))
        {
            array_push($arr2,'Hostel');
        }
        if(isset($_POST['transportation']) && !empty($_POST['transportation']))
        {
            array_push($arr2,'Transportation');
        }
        if(isset($_POST['icourse']) && !empty($_POST['icourse']))
        {
            array_push($arr2,'Course');
        }
        if(isset($_POST['year']) && !empty($_POST['year']))
        {
            array_push($arr2,'Year');
        }
        if(isset($_POST['class_id']) && !empty($_POST['class_id']))
        {
            array_push($arr2,'Class');
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {
            array_push($arr2,'Year');
        }

        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        
        $html_content = '<style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            tr:nth-child(even) {
            background-color: #dddddd;
            }
        </style><table><tr>';
        foreach($header as $hea){
            $html_content .='<th>'.$hea.'</th>';
        }
        $html_content .='</tr>';
        foreach($query as $vale){
            $table_date = array();
            $html_content .='<tr>';
            foreach($vale as $keye=>$va){
                //array_push($table_date,$vale[$keye]);
                $html_content .='<td>'.$vale[$keye].'</td>';
            }
            $html_content .='</tr>';
        }
        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("Students".date('d-m-y:h:i:s').".pdf");
        exit;
    } 
    
    public function downloadPDFManagement()
    {
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
                        $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'3'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'3'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        
        $html_content = '<style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            
            tr:nth-child(even) {
            background-color: #dddddd;
            }
        </style><table><tr>';
        foreach($header as $hea){
            $html_content .='<th>'.$hea.'</th>';
        }
        $html_content .='</tr>';
        foreach($query as $vale){
            $table_date = array();
            $html_content .='<tr>';
            foreach($vale as $keye=>$va){
            //array_push($table_date,$vale[$keye]);
            $html_content .='<td>'.$vale[$keye].'</td>';
        }
        $html_content .='</tr>';
        }
        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("Management_".date('d-m-y:h:i:s').".pdf");
        exit;
    } 
    
    public function downloadManagement()
    {
        $filename = "Management_".date('d-m-y:h:i:s').".csv";
        $fp  = fopen('php://output', 'w');
        
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'3'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'3'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
        foreach($query as $k=>$row){
            $rowData = array();
            foreach($row as $key=>$val){
             array_push($rowData,$row[$key]);
            }
            fputcsv($fp, $rowData);
            $i++;
        }
        //die;
        exit;
    }
    public function downloadPDFAdmins()
    {
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
                        $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'2'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'2'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        
        $html_content = '<style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            
            tr:nth-child(even) {
            background-color: #dddddd;
            }
        </style><table><tr>';
        foreach($header as $hea){
            $html_content .='<th>'.$hea.'</th>';
        }
        $html_content .='</tr>';
        foreach($query as $vale){
            $table_date = array();
            $html_content .='<tr>';
            foreach($vale as $keye=>$va){
            //array_push($table_date,$vale[$keye]);
            $html_content .='<td>'.$vale[$keye].'</td>';
        }
        $html_content .='</tr>';
        }
        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("Office_Admins_".date('d-m-y:h:i:s').".pdf");
        exit;
    }
    public function downloadAdmins()
    {
        $filename = "Office_Admins_".date('d-m-y:h:i:s').".csv";
        $fp  = fopen('php://output', 'w');
        
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'2'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('admin', array('dol' =>'00-00-0000','owner_status' =>'2'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
        foreach($query as $k=>$row){
            $rowData = array();
            foreach($row as $key=>$val){
             array_push($rowData,$row[$key]);
            }
            fputcsv($fp, $rowData);
            $i++;
        }
        //die;
        exit;
    }
    
    public function downloadPDFTeachers()
    {

        $arr1 = array();
        if(!empty($_POST)){
        //unset($_POST['sort_by_designation']);
        foreach($_POST as $post_array)
        {
        array_push($arr1,$post_array);
        }
        }
        
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);

        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('teacher', array('dol' =>'00-00-0000'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('teacher', array('dol' =>'00-00-0000'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
        unset($_POST['sort_by_designation']);
        if($_POST['department']=='department'){
            $_POST['department'] = 'Department';
        }
        if($_POST['designation']=='designation'){
            $_POST['designation'] = 'Designation';
        }
        if($_POST['pan']=='pan'){
            $_POST['pan'] = 'PAN';
        }
        if($_POST['aadhar']=='aadhar'){
            $_POST['aadhar'] = 'Aadhar';
        }
        if($_POST['bank_no']=='bank_no'){
            $_POST['bank_no'] = 'Account Number';
        }
        if($_POST['bank_ifsc']=='bank_ifsc'){
            $_POST['bank_ifsc'] = 'IFSC Code';
        }
        if($_POST['bank_name']=='bank_name'){
            $_POST['bank_name'] = 'Bank Name';
        }
        foreach($_POST as $post_array1)
        {
        array_push($arr2,$post_array1);
        }
        }
        
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        
        
        $html_content = '<style>
        table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }
        
        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
        
        tr:nth-child(even) {
        background-color: #dddddd;
        }
        </style><table><tr>';
        foreach($header as $hea){
        $html_content .='<th>'.$hea.'</th>';
        }
        $html_content .='</tr>';
        foreach($query as $vale){
            $table_date = array();
            $html_content .='<tr>';
            foreach($vale as $keye=>$va){
        //array_push($table_date,$vale[$keye]);
        $html_content .='<td>'.$vale[$keye].'</td>';
        }
        $html_content .='</tr>';
        }
        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->set_option("isPhpEnabled", true);
        $this->pdf->render();
        $this->pdf->stream("Management_".date('d-m-y:h:i:s').".pdf");
        exit;
    } 
    
    public function downloadTeachers()
    {
        $filename = "Teachers_".date('d-m-y:h:i:s').".csv";
        $fp  = fopen('php://output', 'w');
        
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('teacher', array('dol' =>'00-00-0000'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('teacher', array('dol' =>'00-00-0000'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['department']=='department'){
                $_POST['department'] = 'Department';
            }
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
        foreach($query as $k=>$row){
            $rowData = array();
            foreach($row as $key=>$val){
             array_push($rowData,$row[$key]);
            }
            fputcsv($fp, $rowData);
            $i++;
        }
        //die;
        exit;
    }
    
    public function downloadPDFOfficeStaff()
    {
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('officestaff', array('dol' =>'00-00-0000'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('officestaff', array('dol' =>'00-00-0000'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
        unset($_POST['sort_by_designation']);
        if($_POST['department']=='department'){
            $_POST['department'] = 'Department';
        }
        if($_POST['designation']=='designation'){
            $_POST['designation'] = 'Designation';
        }
        if($_POST['pan']=='pan'){
            $_POST['pan'] = 'PAN';
        }
        if($_POST['aadhar']=='aadhar'){
            $_POST['aadhar'] = 'Aadhar';
        }
        if($_POST['bank_no']=='bank_no'){
            $_POST['bank_no'] = 'Account Number';
        }
        if($_POST['bank_ifsc']=='bank_ifsc'){
            $_POST['bank_ifsc'] = 'IFSC Code';
        }
        if($_POST['bank_name']=='bank_name'){
            $_POST['bank_name'] = 'Bank Name';
        }
        foreach($_POST as $post_array1)
        {
        array_push($arr2,$post_array1);
        }
        }
        
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        
        
        $html_content = '<style>
        table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }
        
        td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }
        
        tr:nth-child(even) {
        background-color: #dddddd;
        }
        </style><table><tr>';
        foreach($header as $hea){
        $html_content .='<th>'.$hea.'</th>';
        }
        $html_content .='</tr>';
        foreach($query as $vale){
            $table_date = array();
            $html_content .='<tr>';
            foreach($vale as $keye=>$va){
        //array_push($table_date,$vale[$keye]);
        $html_content .='<td>'.$vale[$keye].'</td>';
        }
        $html_content .='</tr>';
        }
        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("OfficeStaff_".date('d-m-y:h:i:s').".pdf");
        exit;
    } 
    
    public function downloadOfficeStaff()
    {
        $filename = "OfficeStaff_".date('d-m-y:h:i:s').".csv";
        $fp  = fopen('php://output', 'w');
        
        $arr1 = array();
        if(!empty($_POST)){
            //unset($_POST['sort_by_designation']);
            foreach($_POST as $post_array)
            {
             array_push($arr1,$post_array);
            }
        }
        $default_select = array('first_name','email','phone');
        $select = array_merge($default_select,$arr1);
        $select_data = implode(',',$select);
        
        if($_POST['sort_by_designation']=='sort_by_designation')
            $query = $this->db->select(str_replace('sort_by_designation','',$select_data))->order_by("designation_priority ASC,first_name ASC")->get_where('officestaff', array('dol' =>'00-00-0000'))->result_array();
        else
            $query = $this->db->select($select_data)->order_by("first_name ASC")->get_where('officestaff', array('dol' =>'00-00-0000'))->result_array();
        
        $arr2 = array();
        if(!empty($_POST)){
            unset($_POST['sort_by_designation']);
            if($_POST['department']=='department'){
                $_POST['department'] = 'Department';
            }
            if($_POST['designation']=='designation'){
                $_POST['designation'] = 'Designation';
            }
            if($_POST['pan']=='pan'){
                $_POST['pan'] = 'PAN';
            }
            if($_POST['aadhar']=='aadhar'){
                $_POST['aadhar'] = 'Aadhar';
            }
            if($_POST['bank_no']=='bank_no'){
                $_POST['bank_no'] = 'Account Number';
            }
            if($_POST['bank_ifsc']=='bank_ifsc'){
                $_POST['bank_ifsc'] = 'IFSC Code';
            }
            if($_POST['bank_name']=='bank_name'){
                $_POST['bank_name'] = 'Bank Name';
            }
            foreach($_POST as $post_array1)
            {
             array_push($arr2,$post_array1);
            }
        }
        $header_array = array('Full Name','Email','Phone');
        $header = array_merge($header_array,$arr2);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
        foreach($query as $k=>$row){
            $rowData = array();
            foreach($row as $key=>$val){
             array_push($rowData,$row[$key]);
            }
            fputcsv($fp, $rowData);
            $i++;
        }
        //die;
        exit;
    }
    
    
    
    public function updateCurrentAccountant()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['email']        = $this->input->post('email');
        $data['idcard']       = $this->input->post('idcard');
        $data['phone']        = $this->input->post('phone');
        $data['address']      = $this->input->post('address');
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        if($_FILES['userfile']['name'] != ""){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        $this->db->where('accountant_id', $this->session->userdata('login_user_id'));
        $this->db->update('accountant', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/accountant_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function updateCurrentLibrarian()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['email']        = $this->input->post('email');
        $data['idcard']       = $this->input->post('idcard');
        $data['phone']        = $this->input->post('phone');
        $data['address']      = $this->input->post('address');
        if($this->input->post('password') != ""){
            $data['password'] = sha1($this->input->post('password'));   
        }
        if($_FILES['userfile']['name'] != ""){
            $data['image']    = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        $this->db->where('librarian_id', $this->session->userdata('login_user_id'));
        $this->db->update('librarian', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/librarian_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
    
    public function createPublicTeacherAccount()
    {
        $data['first_name']        = $this->input->post('first_name');
        $data['since']     = $this->crud->getDateFormat();
        $data['username']    = $this->input->post('username');
        $data['phone']       = $this->input->post('phone');
        $data['email']        = $this->input->post('email');
        $data['sex']       = $this->input->post('sex');
        $data['birthday']    = $this->input->post('birthday');
        $data['type']    = "teacher";
        $data['password']    = sha1($this->input->post('password'));
        $this->db->insert('pending_users', $data);
        $user_id = $this->db->insert_id();
        $this->mail->welcomeUser($user_id);

        $notify['notify'] = "<strong>".getEduAppGTLang('register').":</strong>,". " ". getEduAppGTLang('reg_teacher') ."<b>".$this->input->post('name')."</b>";
        $admins = $this->db->get('admin')->result_array();
        foreach($admins as $row)
        {
            $notify['user_id'] = $row['admin_id'];
            $notify['user_type'] = 'admin';
            $notify['url'] = "admin/pending/";
            $notify['date'] = $this->crud->getDateFormat();
            $notify['time'] = date('h:i A');
            $notify['status'] = 0;
            $notify['original_id'] = "";
            $notify['original_type'] = "";
            $this->db->insert('notification', $notify);
        }
    }
    
    public function createPublicStudentAccount()
    {
        $data['class_id']    = $this->input->post('class_id');
        $data['section_id']  = $this->input->post('section_id');
        $data['parent_id']   = $this->input->post('parent_id');
        $data['first_name']        = $this->input->post('first_name');
        $data['since']     = $this->crud->getDateFormat();
        $data['username']    = $this->input->post('username');
        $data['phone']       = $this->input->post('phone');
        $data['email']        = $this->input->post('email');
        $data['sex']         = $this->input->post('sex');
        $data['birthday']    = $this->input->post('birthday');
        $data['roll']        = $this->input->post('roll');
        $data['type']        = "student";
        $data['password']    = sha1($this->input->post('password'));
        $this->db->insert('pending_users', $data);
        $user_id = $this->db->insert_id();
        $this->mail->welcomeUser($user_id);

         $notify['notify'] = "<strong>".getEduAppGTLang('register').":</strong>,". " ". getEduAppGTLang('reg_student')."<b>".$this->input->post('name')."</b>";
        $admins = $this->db->get('admin')->result_array();
        foreach($admins as $row)
        {
            $notify['user_id'] = $row['admin_id'];
            $notify['user_type'] = 'admin';
            $notify['url'] = "admin/pending/";
            $notify['date'] = $this->crud->getDateFormat();
            $notify['time'] = date('h:i A');
            $notify['status'] = 0;
            $notify['original_id'] = "";
            $notify['original_type'] = "";
            $this->db->insert('notification', $notify);
        }

    }
    
    public function createPublicParentAccount()
    {
        $data['first_name']        = $this->input->post('first_name');
        $data['email']        = $this->input->post('email');
        $data['since']     = $this->crud->getDateFormat();
        $data['username']    = $this->input->post('username');
        $data['phone']       = $this->input->post('phone');
        $data['profession']    = $this->input->post('profession');
        $data['type']        = "parent";
        $data['password']    = sha1($this->input->post('password'));
        $this->db->insert('pending_users', $data);
        $user_id = $this->db->insert_id();
        $this->mail->welcomeUser($user_id);

        $notify['notify'] = "<strong>".getEduAppGTLang('register').":</strong>,". " ". getEduAppGTLang('reg_parent')."<b>".$this->input->post('name')."</b>";
        $admins = $this->db->get('admin')->result_array();
        foreach($admins as $row)
        {
            $notify['user_id'] = $row['admin_id'];
            $notify['user_type'] = 'admin';
            $notify['url'] = "admin/pending/";
            $notify['date'] = $this->crud->getDateFormat();
            $notify['time'] = date('h:i A');
            $notify['status'] = 0;
            $notify['original_id'] = "";
            $notify['original_type'] = "";
            $this->db->insert('notification', $notify);
        }
    }
    
    public function updateCurrentTeacher()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        $data['email']          = $this->input->post('email');
        $data['phone']          = $this->input->post('phone');
        $data['idcard']         = $this->input->post('idcard');
        $data['birthday']       = $this->input->post('birthday');
        $data['address']        = $this->input->post('address');
        $data['username']       = $this->input->post('username');
        if($_FILES['userfile']['name'] != ""){
            $data['image']      = $md5.str_replace(' ', '', $_FILES['userfile']['name']);
        }
        if($this->input->post('password') != ""){
         $data['password']      = sha1($this->input->post('password'));   
        }
        $this->db->where('teacher_id', $this->session->userdata('login_user_id'));
        $this->db->update('teacher', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'public/uploads/teacher_image/' . $md5.str_replace(' ', '', $_FILES['userfile']['name']));
    }
}