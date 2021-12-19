<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends School 
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
    
    public function createBulkInvoice()
    {
        foreach ($this->input->post('student_id') as $id) 
        {
            $data['student_id']         = $id;
            $data['class_id']           = $this->input->post('class_id');
            $data['title']              = html_escape($this->input->post('title'));
            $data['description']        = html_escape($this->input->post('description'));
            $data['amount']             = html_escape($this->input->post('amount'));
            $data['due']                = $data['amount'];
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = $this->crud->getDateFormat();
            $data['year']               = $this->runningYear;
            $this->db->insert('invoice', $data);
            $invoice_id = $this->db->insert_id();
            $data2['invoice_id']        = $invoice_id;
            $data2['student_id']        = $id;
            $data2['title']             = html_escape($this->input->post('title'));
            $data2['description']       = html_escape($this->input->post('description'));
            $data2['payment_type']      = 'income';
            $data2['method']            = $this->input->post('method');
            $data2['amount']            = html_escape($this->input->post('amount'));
            $data2['timestamp']         = strtotime($this->input->post('date'));
            $data2['month']             = date('M');
            $data2['year']              = $this->runningYear;
            $this->db->insert('payment' , $data2);
        }
    }
    
    public function singleInvoice()
    {
        //print_r($_POST); die;
        $studentid = $data['student_id'] = $this->input->post('student_id');
        $data['class_id']           = $this->db->get_where('student' , array('student_id' => $studentid))->row()->combination;
        $data['title']              = implode(',',$this->input->post('title'));
        $data['amount']             = implode(',',$this->input->post('amount'));
        $data['due']                = $data['amount'];
        $data['creation_timestamp'] = $this->input->post('date');
        $data['collected_by']       = $this->input->post('collected_by');
        $data['designation']        = $this->input->post('designation');
        $data['status']             = "completed";
        $data['method']             = $this->input->post('method');
        $data['payment_method']     = $this->input->post('method');
        $data['year']               = $this->runningYear;
        $data['reference']          = $this->input->post('reference');
        $data['chq_number']         = $this->input->post('chq_number');
        $data['chq_bank_name']      = $this->input->post('chq_bank_name');
        $data['chq_bank_branch']    = $this->input->post('chq_bank_branch');
        //echo '<pre>';
        //print_r($data); die;
        $this->db->insert('invoice', $data);
        $invoice_id = $this->db->insert_id();
        $data2['invoice_id']        = $invoice_id;
        $data2['student_id']        = $this->input->post('student_id');
        $data2['title']             = implode(',',$this->input->post('title'));
        $data2['payment_type']      =  'income';
        $data2['method']            = $this->input->post('method');
        $data2['amount']            = implode(',',$this->input->post('amount'));
        $data2['timestamp']         = $this->input->post('date');//strtotime($this->input->post('date'));
        $data2['month']             = date('M');
        $data2['year']              = $this->runningYear;
        $data2['collected_by']      = $this->input->post('collected_by');
        $data2['designation']       = $this->input->post('designation');        
        $data2['reference']         = $this->input->post('reference');
        $data2['chq_number']        = $this->input->post('chq_number');
        $data2['chq_bank_name']     = $this->input->post('chq_bank_name');
        $data2['chq_bank_branch']   = $this->input->post('chq_bank_branch');        
        $this->db->insert('payment' , $data2);
        $student_name     = $this->crud->get_name('student', $this->input->post('student_id'));
        $student_email    = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->email;
        $student_phone    = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->phone;
        $parent_id        = $this->db->get_where('student', array('student_id' => $this->input->post('student_id')))->row()->parent_id;
        $parent_phone     = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->phone;
        $parent_email     = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->email;
        $notify           = $this->crud->getInfo('p_new_invoice');
        $notify2          = $this->crud->getInfo('s_new_invoice');
        $message          = getEduAppGTLang('new_invoice_has_been_generated_for')." " . $student_name;
        $sms_status       = $this->crud->getInfo('sms_status');
        if($notify == 1)
        {
            if ($sms_status == 'msg91') 
            {
                $result = $this->crud->send_sms_via_msg91($message, $parent_phone);
            }
            else if ($sms_status == 'twilio') 
            {
                $this->crud->twilio_api($message,"".$parent_phone."");
            }
            else if ($sms_status == 'clickatell') 
            {
                $this->crud->clickatell($message,$parent_phone);
            }
        }
        $this->crud->parent_new_invoice($student_name, "".$parent_email."");
        if($notify2 == 1)
        {
          if ($sms_status == 'msg91') 
          {
             $result = $this->crud->send_sms_via_msg91($message, $student_phone);
          }
          else if ($sms_status == 'twilio') 
          {
              $this->crud->twilio_api($message,"".$student_phone."");
          }
          else if ($sms_status == 'clickatell') 
          {
              $this->crud->clickatell($message,$student_phone);
          }
        }
        $this->crud->student_new_invoice($student_name, "".$student_email."");
    }
    
    public function updateInvoice($invoiceId)
    {
        $data['title']              = $this->input->post('title');
        $data['description']        = $this->input->post('description');
        $data['amount']             = $this->input->post('amount');
        $data['status']             = $this->input->post('status');

        $this->db->where('invoice_id', $invoiceId);
        $this->db->update('invoice', $data);
    }
    
    public function deleteInvoice($invoiceId)
    {
        $this->db->where('invoice_id', $invoiceId);
        $this->db->delete('invoice');
    }
    
    public function makePayPal()
    {
        $type = '';
        if($this->session->userdata('login_type') == 'parent')
        {
            $type = 'parents';
        }else{
            $type = 'student';
        }
        $invoice_id      = $this->input->post('invoice_id');
        $system_settings = $this->db->get_where('settings', array('type' => 'paypal_email'))->row();
        $invoice_details = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row();
        $this->paypal->add_field('rm', 2);
        $this->paypal->add_field('no_note', 0);
        $this->paypal->add_field('item_name', $invoice_details->title);
        $this->paypal->add_field('amount', $invoice_details->due);
        $this->paypal->add_field('currency_code', $this->db->get_where('settings' , array('type' =>'currency'))->row()->description);
        $this->paypal->add_field('custom', $invoice_details->invoice_id);
        $this->paypal->add_field('business', $system_settings->description);
        $this->paypal->add_field('notify_url', base_url() . $type.'/invoice/');
        $this->paypal->add_field('cancel_return', base_url() . $type.'/invoice/paypal_cancel');
        $this->paypal->add_field('return', base_url() . $type.'/invoice/paypal_success');
        $this->paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        $this->paypal->submit_paypal_post();
    }
    
    public function paypalSuccess()
    {
        foreach ($_POST as $key => $value) 
        {
            $value = urlencode(stripslashes($value));
            $ipn_response .= "\n$key=$value";
        }
        $data['payment_details']   = $ipn_response;
        $data['payment_timestamp'] = strtotime(date("m/d/Y"));
        $data['payment_method']    = 'paypal';
        $data['status']            = 'completed';
        $invoice_id                = $_POST['custom'];
        $this->db->where('invoice_id', $invoice_id);
        $this->db->update('invoice', $data);

        $data2['method']       =   'paypal';
        $data2['invoice_id']   =   $_POST['custom'];
        $data2['timestamp']    =   strtotime(date("m/d/Y"));
        $data2['payment_type'] =   'income';
        $data2['title']        =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->title;
        $data2['description']  =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->description;
        $data2['student_id']   =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->student_id;
        $data2['amount']       =   $this->db->get_where('invoice' , array('invoice_id' => $data2['invoice_id']))->row()->amount;
        $this->db->insert('payment' , $data2);
    }
    
       public function genratereports()
    {
        $startdata = $_REQUEST['startdata'];
        $enddata = $_REQUEST['enddata'];
        if(isset($_REQUEST['ftype']) && !empty($_REQUEST['ftype']))
        {
            $myftype = implode('", "', $_POST['ftype']);
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {   
           $mystdyear = implode('", "', $_POST['stdyear']);
        }

        if(isset($_POST['fmode']) && !empty($_POST['fmode']))
        {   
           $mysfmode = implode('", "', $_POST['fmode']);
        }
        
        $conmyclass_id = !empty($myftype) ? 'invoice.title IN ("' .$myftype. '")' : '1=1';
        $conmymystdyear = !empty($mystdyear) ? 'student.stdyear IN ("' .$mystdyear. '")' : '1=1';
        $conmymysfmode = !empty($mysfmode) ? 'invoice.payment_method IN ("' .$mysfmode. '")' : '1=1';
        
        
    return $this->db->query("SELECT student.stdyear as stdyear, class.name as calssname, student.first_name as fullname,student.email as semail, student.phone as sphone,invoice.payment_method as method, invoice.amount as amount, invoice.creation_timestamp as creation_timestamp, invoice.invoice_id as invoice_id, invoice.title as title FROM invoice LEFT JOIN student ON invoice.student_id=student.student_id  LEFT JOIN class ON class.class_id=student.combination WHERE $conmyclass_id AND $conmymysfmode AND $conmymystdyear AND invoice.creation_timestamp BETWEEN '$startdata' AND '$enddata'")->result_array(); 
    
    }
    
    public function downloadAccountReportsE()
    {
         $startdata = $_REQUEST['startdate'];
        $enddata = $_REQUEST['enddate'];
        if(isset($_REQUEST['feetype']) && !empty($_REQUEST['feetype']))
        {
            $myftype = implode('", "', $_POST['feetype']);
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {   
           $mystdyear = implode('", "', $_POST['stdyear']);
        }

        if(isset($_POST['paymode']) && !empty($_POST['paymode']))
        {   
           $mysfmode = implode('", "', $_POST['paymode']);
        }
        
        $conmyclass_id = !empty($myftype) ? 'invoice.title IN ("' .$myftype. '")' : '1=1';
        $conmymystdyear = !empty($mystdyear) ? 'student.stdyear IN ("' .$mystdyear. '")' : '1=1';
        $conmymysfmode = !empty($mysfmode) ? 'invoice.payment_method IN ("' .$mysfmode. '")' : '1=1';
        

        $filename = "AccountReport_".$startdata.'_To_'.$enddata.".csv";
        $fp  = fopen('php://output', 'w');

        $query = $this->db->query("SELECT invoice.invoice_id as invoice_id, student.first_name as fullname,student.phone as sphone,student.email as semail, student.stdyear as stdyear, class.name as calssname,invoice.payment_method as method, invoice.creation_timestamp as creation_timestamp, invoice.title as title,invoice.amount as amount FROM invoice LEFT JOIN student ON invoice.student_id=student.student_id  LEFT JOIN class ON class.class_id=student.combination WHERE $conmyclass_id AND $conmymysfmode AND $conmymystdyear AND invoice.creation_timestamp BETWEEN '$startdata' AND '$enddata'")->result_array(); 

               $header = array('INVOICE ID','STUDENT NAME','STUDENT PHONE','STUDENT EMAIL','YEAR','CLASS','PAYMENT MODE','PAID DATE','FEE TYPE','AMOUNT');
               header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        $i=1;
    $totalfees = 0;
    $totalcasefees = 0;
    $totalchequefees = 0;
    $totalcardfees = 0;
    $totalupifees = 0;
    $firsyear = 0;
    $seconyear = 0;
    $first = 0;
    $scond = 0;
    $thired = 0;
    $four = 0;
    $five = 0;
    $six = 0;

        foreach($query as $k=>$row)
        {
            $rowData = array();

            foreach($row as $key=>$val)
            {
                 if($key=='invoice_id')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='fullname')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='sphone')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='semail')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='stdyear')
                {
                    if($row[$key]=='I')
                        {
                            $hostaldata = '1st Year';
                            $firsyear = $firsyear+$row['amount'];
                        }
                        else
                        {
                            $hostaldata = '2nd Year';
                            $seconyear = $seconyear+$row['amount'];
                        }
                        array_push($rowData,$hostaldata);
                 
                }
                
                if($key=='calssname')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='method')
                {
                    if($row[$key]=='1')
                    {
                       $hostaldata2 = 'Cash';
                       $totalcasefees = $totalcasefees+$row['amount'];
                    }
                    else if($row[$key]=='2')
                    {
                     $hostaldata2 = 'Cheque';   
                     $totalchequefees = $totalchequefees+$row['amount'];
                    }
                    else if($row[$key]=='3')
                    {
                     $hostaldata2 = 'Credit/Debit Card';   
                     $totalcardfees = $totalcardfees+$row['amount'];
                    }
                    else if($row[$key]=='4')
                    {
                     $hostaldata2 = 'Online/UPI';   
                     $totalupifees = $totalupifees+$row['amount'];
                    }

                 array_push($rowData,$hostaldata2);
                }
                if($key=='creation_timestamp')
                {
                 array_push($rowData,$row[$key]);
                }

                if($key=='title')
                {
                    if($row[$key]=='COLLEGE FEES')
                    {
                     
                       $first = $first+$row['amount'];
                    }
                    else if($row[$key]=='UNIFORM FEES')
                    {
                     
                     $scond = $scond+$row['amount'];
                    }
                    else if($row[$key]=='BOOKS FEES')
                    {
                     
                     $thired = $thired+$row['amount'];
                    }
                    else if($row[$key]=='LAB COAT FEES')
                    {
                     
                     $four = $four+$row['amount'];
                    }
                    else if($row[$key]=='HOSTEL FEES')
                    {
                     
                     $five = $five+$row['amount'];
                    }
                    else if($row[$key]=='TRANSPORTATION FEE')
                    {
                     
                     $six = $six+$row['amount'];
                    }


                 array_push($rowData,$row[$key]);
                }

                if($key=='amount')
                {
                    $totalfees = $totalfees+$row[$key];
                 array_push($rowData,$row[$key]);
                }

               //array_push($rowData,$row[$key]); 
            }
            fputcsv($fp, $rowData);
            $i++;
        }
    $rang = $startdata.' TO '.$enddata;
        $header4 = array('','','','','','','','','','');
        fputcsv($fp, $header4);
        $header2 = array('Report Summary','','','','','','Date Range',$rang,'Total',$totalfees);
        fputcsv($fp, $header2);
            
    foreach($_POST['paymode'] as $rowe1=>$vale2)
        {
         if($vale2=='1')
         {
        $header3 = array('Cash',$totalcasefees,'','','','','','','','');        
         }   
         else if($vale2=='2')
         {
        $header3 = array('Cheque',$totalchequefees,'','','','','','','','');        
         }
         else if($vale2=='3')
         {
        $header3 = array('Credit/Debit Card',$totalcardfees,'','','','','','','','');        
         }
         else if($vale2=='4')
         {
        $header3 = array('Online/UPI',$totalupifees,'','','','','','','','');        
         }

        fputcsv($fp, $header3);
        }

        foreach($_POST['stdyear'] as $rowe=>$vale)
        {
         if($vale=='I')
         {
        $header3 = array('1st year',$firsyear,'','','','','','','','');        
         }   
         else if($vale=='II')
         {
        $header3 = array('2nd year',$seconyear,'','','','','','','','');        
         }
        fputcsv($fp, $header3);
        }

          foreach($_POST['feetype'] as $rowe3=>$vale3)
        {
         if($vale3=='COLLEGE FEES')
         {
        $header3 = array('COLLEGE FEES',$first,'','','','','','','','');        
         }   
         else if($vale3=='UNIFORM FEES')
         {
        $header3 = array('UNIFORM FEES',$scond,'','','','','','','','');        
         }
         else if($vale3=='BOOKS FEES')
         {
        $header3 = array('BOOKS FEES',$thired,'','','','','','','','');        
         }
         else if($vale3=='LAB COAT FEES')
         {
        $header3 = array('LAB COAT FEES',$four,'','','','','','','','');        
         }
         else if($vale3=='HOSTEL FEES')
         {
        $header3 = array('HOSTEL FEES',$five,'','','','','','','','');        
         }
         else if($vale3=='TRANSPORTATION FEE')
         {
        $header3 = array('TRANSPORTATION FEE',$six,'','','','','','','','');        
         }

        fputcsv($fp, $header3);
        }
        
        
        
        exit;
    }


    public function downloadAccountReportsP()
    {
         $startdata = $_REQUEST['startdate'];
        $enddata = $_REQUEST['enddate'];
        if(isset($_REQUEST['feetype']) && !empty($_REQUEST['feetype']))
        {
            $myftype = implode('", "', $_POST['feetype']);
        }
        if(isset($_POST['stdyear']) && !empty($_POST['stdyear']))
        {   
           $mystdyear = implode('", "', $_POST['stdyear']);
        }

        if(isset($_POST['paymode']) && !empty($_POST['paymode']))
        {   
           $mysfmode = implode('", "', $_POST['paymode']);
        }

        $totalfees = 0;
    $totalcasefees = 0;
    $totalchequefees = 0;
    $totalcardfees = 0;
    $totalupifees = 0;
    $firsyear = 0;
    $seconyear = 0;
    $first = 0;
    $scond = 0;
    $thired = 0;
    $four = 0;
    $five = 0;
    $six = 0;
        
        $conmyclass_id = !empty($myftype) ? 'invoice.title IN ("' .$myftype. '")' : '1=1';
        $conmymystdyear = !empty($mystdyear) ? 'student.stdyear IN ("' .$mystdyear. '")' : '1=1';
        $conmymysfmode = !empty($mysfmode) ? 'invoice.payment_method IN ("' .$mysfmode. '")' : '1=1';
        

        $filename = "AccountReport_".$startdata.'_To_'.$enddata.".csv";
        $fp  = fopen('php://output', 'w');

        $query = $this->db->query("SELECT invoice.invoice_id as invoice_id, student.first_name as fullname,student.phone as sphone,student.email as semail, student.stdyear as stdyear, class.name as calssname,invoice.payment_method as method, invoice.creation_timestamp as creation_timestamp, invoice.title as title,invoice.amount as amount FROM invoice LEFT JOIN student ON invoice.student_id=student.student_id  LEFT JOIN class ON class.class_id=student.combination WHERE $conmyclass_id AND $conmymysfmode AND $conmymystdyear AND invoice.creation_timestamp BETWEEN '$startdata' AND '$enddata'")->result_array(); 

$header = array('INVOICE ID','STUDENT DETAILS','CLASS DETAILS','PAYMENT MODE','PAID DATE','FEE TYPE','AMOUNT');
    
    $html_content ='<div class="row" style="width: 100%;clear: both;">
    <div class="col" style="width: 10%;float: left; padding-top:10px"><img src="https://campus.sirishrine.com/public/uploads/a1c6ee0d9e562a4e67d476eba44e4ed1logo.png" width="100px" height="100px"></div><div style="width: 85%;float: right;" class="col company-details"><h2 style="text-align:center" class="name">SIRI SHRINE PRE UNIVERSITY COLLEGE</h2><div>No.42, Near Gopasandra Gate, Muthanallur Post, Sarjapura Hobli, Anekal Taluk, Bangalore-99 +91 636 456 555 2 | sirishrine@gmail.com
</div></div><br><br><br><br><br><br><br><br>'; 
    $html_content .= '<style>
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
            foreach($vale as $key=>$va){

                     if($key=='invoice_id')
                {
                 $html_content .='<td>'.$vale[$key].'</td>';
                }

                if($key=='fullname' || $key=='sphone' || $key=='semail')
                {
                    if($key=='fullname')
                    {
                    $html_content .='<td> Name : '.$vale[$key].'<br>';  
                    }       
                    
                    if($key=='sphone')
                    {
                    $html_content .='Phone :'.$vale[$key].'<br>';
                    }

                    if($key=='semail')
                    {
                    $html_content .='Email :'.$vale[$key].'</td>';
                    }

                }

                
                if($key=='stdyear' || $key=='calssname')
                {
                    if($key=='stdyear')
                    {       
                    if($vale[$key]=='I')
                        {
                            $hostaldata = '1st Year';
                            $firsyear = $firsyear+$vale['amount'];
                        }
                        else
                        {
                            $hostaldata = '2nd Year';
                            $seconyear = $seconyear+$vale['amount'];
                        }
                        $html_content .='<td>'.$hostaldata.'<br>';
                 }
                     if($key=='calssname')
                    {
                     $html_content .=''.$vale[$key].'</td>';
                    }

                }
                
                

                if($key=='method')
                {
                    if($vale[$key]=='1')
                    {
                       $hostaldata2 = 'Cash';

                       $totalcasefees = $totalcasefees+$vale['amount'];
                    }
                    else if($vale[$key]=='2')
                    {
                     $hostaldata2 = 'Cheque';   
                       $totalchequefees = $totalchequefees+$vale['amount'];
                    }
                    else if($vale[$key]=='3')
                    {
                     $hostaldata2 = 'Credit/Debit Card';   
                       $totalcardfees = $totalcardfees+$vale['amount'];
                    }
                    else if($vale[$key]=='4')
                    {
                     $hostaldata2 = 'Online/UPI';   
                       $totalupifees = $totalupifees+$vale['amount'];
                    }

            $html_content .='<td>'.$hostaldata2.'</td>';
                 
                }
                if($key=='creation_timestamp')
                {
                 $html_content .='<td>'.$vale[$key].'</td>';
                }

                if($key=='title')
                {
                      if($vale[$key]=='COLLEGE FEES')
                    {
                     
                       $first = $first+$vale['amount'];
                    }
                    else if($vale[$key]=='UNIFORM FEES')
                    {
                     
                     $scond = $scond+$vale['amount'];
                    }
                    else if($vale[$key]=='BOOKS FEES')
                    {
                     
                     $thired = $thired+$vale['amount'];
                    }
                    else if($vale[$key]=='LAB COAT FEES')
                    {
                     
                     $four = $four+$vale['amount'];
                    }
                    else if($vale[$key]=='HOSTEL FEES')
                    {
                     
                     $five = $five+$vale['amount'];
                    }
                    else if($vale[$key]=='TRANSPORTATION FEE')
                    {
                     
                     $six = $six+$vale['amount'];
                    }


                 $html_content .='<td>'.$vale[$key].'</td>';
                }

                if($key=='amount')
                {
                    $totalfees = $totalfees+$vale[$key];
                 $html_content .='<td>'.$vale[$key].'</td>';
                }
                //array_push($table_date,$vale[$keye]);

                
            }
            $html_content .='</tr>';
        }
        $rang = $startdata.' TO '.$enddata;
        $html_content .='<tr><td>Date Range</td><td>'.$rang.'</td><td>Total : </td><td>'.$totalfees.'</td></tr>';
        $html_content .='<tr><td>Report Summary</td></tr>';

        foreach($_POST['stdyear'] as $rowe=>$vale)
        {
         if($vale=='I')
         {
        $html_content .='<tr><td>1st year</td><td>'.$firsyear.'</td></tr>';
         }   
         else if($vale=='II')
         {
        $html_content .='<tr><td>2nd year</td><td>'.$seconyear.'</td></tr>';
         }
        
        }

         foreach($_POST['feetype'] as $rowe3=>$vale3)
        {
         if($vale3=='COLLEGE FEES')
         {
        $html_content .='<tr><td>COLLEGE FEES</td><td>'.$first.'</td></tr>';
         }   
         else if($vale3=='UNIFORM FEES')
         {
        $html_content .='<tr><td>UNIFORM FEES</td><td>'.$scond.'</td></tr>';
         }
         else if($vale3=='BOOKS FEES')
         {
        $html_content .='<tr><td>BOOKS FEES</td><td>'.$thired.'</td></tr>';
         }
         else if($vale3=='LAB COAT FEES')
         {
        $html_content .='<tr><td>LAB COAT FEES</td><td>'.$four.'</td></tr>';
         }
         else if($vale3=='HOSTEL FEES')
         {
        $html_content .='<tr><td>HOSTEL FEES</td><td>'.$five.'</td></tr>';
         }
         else if($vale3=='TRANSPORTATION FEE')
         {
        $html_content .='<tr><td>TRANSPORTATION FEE</td><td>'.$six.'</td></tr>';
         }

        }

           foreach($_POST['paymode'] as $rowe1=>$vale2)
        {
         if($vale2=='1')
         {
        $html_content .='<tr><td>Cash</td><td>'.$totalcasefees.'</td></tr>';      
         }   
         else if($vale2=='2')
         {
        
        $html_content .='<tr><td>Cheque</td><td>'.$totalchequefees.'</td></tr>';
         }
         else if($vale2=='3')
         {
        $html_content .='<tr><td>Credit/Debit Card</td><td>'.$totalcardfees.'</td></tr>';
         }
         else if($vale2=='4')
         {
        $html_content .='<tr><td>Online/UPI</td><td>'.$totalupifees.'</td></tr>';
         }

        }

        $html_content .='</table>';
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("AccountReport_".$startdata.'_To_'.$enddata.".pdf");
        exit;
    }
    
    public function marks_report_student()
    {
         $class_id = $_POST['class_id'];
         $exam_id = $_POST['exam_id'];

       $conmyclass_id = !empty($class_id) ? 'class.class_id = "' .$class_id. '"' : '1=1'; 
     $conmyclass_id2 = !empty($exam_id) ? 'student.stdyear = "' .$exam_id. '"' : '1=1';
       //echo 'SELECT student.first_name as fullname FROM student LEFT JOIN class ON class.class_id=student.combination WHERE $conmyclass_id2 AND $conmyclass_id';
    return $this->db->query("SELECT student.first_name as fullname,student.student_id as sid FROM student LEFT JOIN class ON class.class_id=student.combination WHERE $conmyclass_id2 AND $conmyclass_id")->result_array();  
    
    }
    
}