<?php $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description; ?>
    <div class="content-w"> 
	    <?php include 'fancy.php';?>
        <div class="header-spacer"></div>
        <div class="conty">
            <div class="os-tabs-w menu-shad">
                <div class="os-tabs-controls">
                    <ul class="navs navs-tabs upper">
                        <li class="navs-item">
                            <a class="navs-links" href="<?php echo base_url();?>accountant/payments/"><i class="os-icon picons-thin-icon-thin-0482_gauge_dashboard_empty"></i><span><?php echo getEduAppGTLang('home');?></span></a>
                        </li>
                        <li class="navs-item">
                            <a class="navs-links active" href="<?php echo base_url();?>accountant/students_payments/"><i class="os-icon picons-thin-icon-thin-0426_money_payment_dollars_coins_cash"></i><span><?php echo getEduAppGTLang('payments');?></span></a>
                        </li>
                        <li class="navs-item">
                            <a class="navs-links" href="<?php echo base_url();?>accountant/expense/"><i class="os-icon picons-thin-icon-thin-0420_money_cash_coins_payment_dollars"></i><span><?php echo getEduAppGTLang('expense');?></span></a>
                        </li>
                        <li class="navs-item">
                            <a class="navs-links" href="<?php echo base_url();?>accountant/feetypes/"><i class="os-icon picons-thin-icon-thin-0106_clipboard_box_archive_documents"></i><span><?php echo getEduAppGTLang('Fee_Type');?></span></a>
                        </li>                        
                    </ul>
                </div>
            </div><br>
            <div class="content-i">
                <div class="content-box">
	                <div class="element-wrapper">
		                <div class="os-tabs-w">
			                <div class="os-tabs-controls">
			                    <ul class="navs navs-tabs upper">
				                    <li class="navs-item">
				                        <a class="navs-links active" data-toggle="tab" href="#single"><?php echo getEduAppGTLang('Single_Receipt');?></a>
				                    </li>
				                    <li class="navs-item">
				                        <a class="navs-links" data-toggle="tab" href="#bulk"><?php echo getEduAppGTLang('Bulk_Receipt');?></a>
				                    </li>
			                    </ul>
			                </div>
		                </div>
		                <div class="tab-content">
			                <div class="tab-pane active" id="single">
			                    <?php echo form_open(base_url() . 'accountant/invoice/create');?>
			                    <div class="row">
			                        <div class="col-sm-12">
                                        <style>
                                            body{margin-top:10px;
                                            background-color: #f7f7ff;
                                            }
                                            #invoice {
                                                padding: 0px;
                                            }
                                            .invoice {
                                                position: relative;
                                                background-color: #FFF;
                                                min-height: 680px;
                                                padding: 10px
                                            }
                                            .invoice header {
                                                padding: 10px 0;
                                                margin-bottom: 10px;
                                                border-bottom: 1px solid #0d6efd
                                            }
                                            .invoice .company-details {
                                                text-align: right
                                            }
                                            .invoice .company-details .name {
                                                margin-top: 0;
                                                margin-bottom: 0
                                            }
                                            .invoice .contacts {
                                                margin-bottom: 10px
                                            }
                                            .invoice .invoice-to {
                                                text-align: left
                                            }
                                            .invoice .invoice-to .to {
                                                margin-top: 0;
                                                margin-bottom: 0
                                            }
                                            .invoice .invoice-details {
                                                text-align: right
                                            }
                                            .invoice .invoice-details .invoice-id {
                                                margin-top: 0;
                                                color: #0d6efd
                                            }
                                            .invoice main {
                                                padding-bottom: 5px
                                            }
                                            .invoice main .thanks {
                                                margin-top: -100px;
                                                font-size: 2em;
                                                margin-bottom: 12px
                                            }
                                            .invoice main .notices {
                                                padding-left: 6px;
                                                border-left: 6px solid #0d6efd;
                                                background: #e7f2ff;
                                                padding: 10px;
                                            }
                                            .invoice main .notices .notice {
                                                font-size: 1.2em
                                            }
                                            .invoice table {
                                                width: 100%;
                                                border-collapse: collapse;
                                                border-spacing: 0;
                                                margin-bottom: 10px
                                            }
                                            .invoice table td,
                                            .invoice table th {
                                                padding: 15px;
                                                background: #eee;
                                                border-bottom: 1px solid #fff
                                            }
                                            .invoice table th {
                                                white-space: nowrap;
                                                font-weight: 400;
                                                font-size: 18px
                                            }
                                            .invoice table td h3 {
                                                margin: 0;
                                                font-weight: 400;
                                                color: #0d6efd;
                                                font-size: 1.2em
                                            }
                                            .invoice table .qty,
                                            .invoice table .total,
                                            .invoice table .unit {
                                                text-align: right;
                                                font-size: 1.2em
                                            }
                                            .invoice table .no {
                                                color: #fff;
                                                font-size: 1.6em;
                                                background: #0d6efd
                                            }
                                            .invoice table .unit {
                                                background: #ddd
                                            }
                                            .invoice table .total {
                                                background: #0d6efd;
                                                color: #fff
                                            }
                                            
                                            .invoice table tbody tr:last-child td {
                                                border: none
                                            }
                                            
                                            .invoice table tfoot td {
                                                background: 0 0;
                                                border-bottom: none;
                                                white-space: nowrap;
                                                text-align: right;
                                                padding: 10px 20px;
                                                font-size: 1.2em;
                                                border-top: 1px solid #aaa
                                            }
                                            
                                            .invoice table tfoot tr:first-child td {
                                                border-top: none
                                            }
                                            .card {
                                                position: relative;
                                                display: flex;
                                                flex-direction: column;
                                                min-width: 0;
                                                word-wrap: break-word;
                                                background-color: #fff;
                                                background-clip: border-box;
                                                border: 0px solid rgba(0, 0, 0, 0);
                                                border-radius: .25rem;
                                                margin-bottom: 1.5rem;
                                                box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
                                            }
                                            .invoice table tfoot tr:last-child td {
                                                color: #0d6efd;
                                                font-size: 1.4em;
                                                border-top: 1px solid #0d6efd
                                            }
                                            .invoice table tfoot tr td:first-child {
                                                border: none
                                            }
                                            .invoice footer {
                                                width: 100%;
                                                text-align: center;
                                                color: #777;
                                                border-top: 1px solid #aaa;
                                                padding: 8px 0
                                            }
                                            @media print {
                                                .invoice {
                                                    font-size: 14px !important;
                                                    overflow: hidden !important
                                                }
                                                .invoice footer {
                                                    position: absolute;
                                                    bottom: 4px;
                                                    page-break-after: always
                                                }
                                                .invoice>div:last-child {
                                                    page-break-before: always
                                                }
                                            }
                                            .invoice main .notices {
                                                padding-left: 6px;
                                                border-left: 6px solid #0d6efd;
                                                background: #e7f2ff;
                                                padding: 5px;
                                            }
                                        </style> 			                            
                                        <div class="card">
                                            <div class="card-body">
                                                <div id="invoice">
                                                    <div class="invoice overflow-auto">
                                                        <div style="min-width: 600px">
                                                            <header>
                                                                <div class="row">
                                                                    <div class="col"><img src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>" width="150px" height="150px"></div>
                                                                    <div class="col company-details">
                                                                        <h2 class="name"><?php echo $this->crud->getInfo('system_name');?></h2>
                                                                        <div><?php echo $this->crud->getInfo('address');?></div>
                                                                        <div><?php echo $this->crud->getInfo('phone');?> | <?php echo $this->crud->getInfo('system_email');?></div>
                                                                    </div>
                                                                </div>
                                                            </header>
                                                            <main>
                                                                <div class="row contacts">
                                                                    <div class="col invoice-to">
                                                                        <h4 class="invoice-id" style="color:blue;">RECEIPT TO
                                                                            <select name="student_id" id="student_id" class="form-control selectpicker" required data-live-search="true">
                                    	            			                <option value=""><?php echo getEduAppGTLang('select');?></option>
                                                                                <?php
                                                                                    $students = $this->db->get_where('student', array('student_id !=' => ''))->result_array();
                                                                                    foreach ($students as $students_filter_row) :
                                                                                    $combination = $this->db->get_where('class', array('class_id' => $students_filter_row['combination']))->row()->name;
                                                                                ?>
                                                                                <option data-tokens="<?php echo $stu['first_name'];?>" value="<?php echo $students_filter_row['student_id'];?>"> <?php echo $students_filter_row['first_name'];?> (<?php echo $students_filter_row['stdyear'];?> Year - <?php echo $combination;?>)</option>
                                                                                <?php endforeach; ?>
                                    						                </select>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="col invoice-details">
                                                                        <center><h4 class="invoice-id">Receipt No<br><font color="black">SS<?php echo $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;;?>/<?php echo $this->db->from("invoice")->count_all_results()+1;?></font></h4></center>
                                                                        <input class="btn btn-info btn-sm btn-rounded" type="button" value="+ Add New Payment Type" onclick="addRow()">
                                                                    </div>  
                                                                    <div class="col invoice-details">
                                                                        <h4 class="invoice-id">Date of Receipt<br><input type="text" name="date" value="<?php echo date('d-m-Y');?>"></h4>
                                                                    </div>                                                                
                                                                </div>
                                                                <table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-left">Sl.No</th>
                                                                            <th class="text-center">Payment Description</th>
                                                                            <th colspan="3" class="text-right">Amount</th>
                                                                            <th colspan="3" class="text-right">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>    
                                                                <table>
                                                                    <tbody> 
                                                                        <?php $rowCounter =0;?>
                                                                        <div id="content">
                                                                        <tr>
                                                                            <script>
                                                                               var j=0;
                                                                                function addRow () {
                                                                                    j++;
                                                                                  document.querySelector('#content').insertAdjacentHTML(
                                                                                    'beforebegin',
                                                                                    `<div class="row" style="text-align: center;" id=san-`+j+`>
                                                                                        <div class="col col-lg-1 col-md-1 col-sm-12 col-12">
                                                                                            <div class="form-group label-floating">
                                                                                                  	<h4>`+j+`</4>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col col-lg-6 col-md-6 col-sm-12 col-12"><div class="select">
                                                                                        <select name="title[]" id="title">
                                                                                            <option value=""><?php echo getEduAppGTLang('select');?></option>
                            	                                                            <?php $feetyps_data = $this->db->order_by("feetype_priority ASC")->get_where('feetypes' , array('status' => 1))->result_array();
                                                                                                foreach($feetyps_data as $fee_row):
                              	                                                            ?>
                                                                                            <option value="<?php echo $fee_row['feetype'];?>"><?php echo $fee_row['feetype'];?></option>
                                                                                            <?php endforeach;?>
                                                                                        </select></div></div>
                                                                                        <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                                                                            <div class="form-group label-floating">
                                                                                              	<label class="control-label"><?php echo getEduAppGTLang('amount');?></label>
                                                                                          	    <input class="form-control" name="amount[]" type="text" required="">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col col-lg-1 col-md-1 col-sm-12 col-12">
                                                                                            <div class="form-group label-floating">
                                                                                              	<input class="btn btn-danger btn-xs btn-rounded" type="button" value="X" onclick="removeRow(`+j+`)">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>`      
                                                                                  )
                                                                                }
                                                                                function removeRow (m) {
                                                                                  $('#san-'+m).remove()
                                                                                }
                                                                            </script>                                                                            
                                                                        </tr>
                                                                        </div>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="2"></td>
                                                                            <td colspan="2">GRAND TOTAL</td>
                                                                            <td><?php echo $this->crud->getInfo('currency'); ?><?php echo $row['amount'];?></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                                <br><br>
                                                                <div class="thanks"><br></div>
                                                    			<div class="row">
                                                        			<div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                                                                        <div class="form-group label-floating is-select">
                                                                            <label class="control-label"><?php echo getEduAppGTLang('Payment_Method');?></label>
                                                                            <div class="select">
                                                                                <select name="method" required="">
                                                                                    <option value=""><?php echo getEduAppGTLang('select');?></option>
                                                        					        <option value="1"><?php echo getEduAppGTLang('Cash');?></option>
                                                        					        <option value="2"><?php echo getEduAppGTLang('Cheque');?></option>
                                                        					        <option value="3"><?php echo getEduAppGTLang('Credit/Debit Card');?></option>
                                                        					        <option value="4"><?php echo getEduAppGTLang('Online/UPI');?></option>
                                                                                </select>
                                                                            </div>
                                                                            <script>
                                                                                $("[name='method']").change(function(){ 
                                                                                    if($(this).val() == "2" )
                                                                                    {
                                                                                        $('.chqprof').slideDown();
                                                                                        $('.reference').show();
                                                                                    }
                                                                                    else if($(this).val() == "1" )
                                                                                    {
                                                                                        $('.reference').hide();
                                                                                        $('.chqprof').slideUp();
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $('.reference').show();
                                                                                        $('.chqprof').slideUp();
                                                                                    }
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12 reference" style="display:none;" >
                                                        			    <div class="form-group label-floating">
                                                                            <label class="control-label"><?php echo getEduAppGTLang('Reference Number');?></label>
                                                                            <input class="form-control" name="reference" type="text" >
                                                        			    </div>
                                                        			</div>
                                                                </div>
                                                                <div class="row chqprof" style="display:none;" >
                	                                                <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                                                        <div class="form-group label-floating">
                                                                          	<label class="control-label"><?php echo getEduAppGTLang('Chq Number');?></label>
                                                                      	    <input class="form-control" name="chq_number" type="text">
                                                                      	</div>
                                                                    </div>
                		                                            <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                                                        <div class="form-group label-floating">
                                                                          	<label class="control-label"><?php echo getEduAppGTLang('Chq Bank Name');?></label>
                                                                      	    <input class="form-control" name="chq_bank_name" type="text">
                                                                      	</div>
                                                                    </div>
                		                                            <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                                                                        <div class="form-group label-floating">
                                                                          	<label class="control-label"><?php echo getEduAppGTLang('Chq Bank Branch');?></label>
                                                                      	    <input class="form-control" name="chq_bank_branch" type="text">
                                                                      	</div>
                                                                    </div>
                                                                </div>                     
            													<div class="invoice-footer" style="margin-top: 0px;">
                                    			                    <div class="invoice-logo">
                        	                                            <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                                            <button class="btn btn-success btn-rounded" type="submit"><?php echo getEduAppGTLang('Create_Receipt');?></button>
                                                                        </div>
                                    			                    </div>
                                    			                    <div class="invoice-info">
                                                                        <center>
                                                                            <?php echo $this->crud->get_name('accountant', $this->session->userdata('login_user_id'));?>
                                                                            <input class="form-control" name="designation" type="hidden"  value="<?php echo $this->crud->get_admin_role('admin', $this->session->userdata('login_user_id'));?>">
                                                                            <input class="form-control" name="collected_by" type="hidden" value="<?php echo $this->crud->get_name('admin', $this->session->userdata('login_user_id'));?>"
                                                                            <span class="author-subtitle">Accountant</span><br>
                                                                        </center>
                                                                        <img style="width:25px;height:25px;" src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>"><span><?php echo $this->crud->getInfo('system_name');?></span>
                                    			                    </div>
                                    		                    </div>
                                    		                    <hr style="border-top: 3px dashed green;">
                                                            </main>
                                                        </div>
                                                        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
	                            </div>
	                            <?php echo form_close();?>
          	                </div>
		  	                <div class="tab-pane" id="bulk">
		  		                <?php echo form_open(base_url() . 'accountantinvoice/bulk', array('class' => 'form-horizontal form-groups-bordered validate', 'id'=> 'mass' ,'target'=>'_top'));?>
		  	                        <div class="row">
			                            <div class="col-sm-6">
                                            <div class="element-box lined-primary shadow">
		                                        <h5 class="form-header"><?php echo getEduAppGTLang('Receipt_Details');?></h5><br>
		                                        <div class="row">
                                        		    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group label-floating is-select">
                                                            <label class="control-label"><?php echo getEduAppGTLang('class');?></label>
                                                            <div class="select">
                                                                <select name="class_id" required="" class="class_id" onchange="return get_class_students_mass(this.value)">
                                                                    <option value=""><?php echo getEduAppGTLang('select');?></option>
                                                                    <?php 
                                                                        $classes = $this->db->get('class')->result_array();
                                                                        foreach ($classes as $row):
                                                                    ?>
                                                                        <option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                                                    <?php endforeach;?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group label-floating">
                                                            <label class="control-label"><?php echo getEduAppGTLang('amount');?></label>
                                                          	<input class="form-control" name="amount" type="text" required="">
                                                        </div>
                                                    </div>
                                        		    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group label-floating">
                                                          	<label class="control-label"><?php echo getEduAppGTLang('title');?></label>
                                                          	<input class="form-control" name="title" type="text" required="">
                                                        </div>
                                                    </div>
                                        		    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                        				<div class="form-group label-floating is-empty">
                                        				    <label class="control-label"><?php echo getEduAppGTLang('description');?>:</label>
                                        				    <textarea class="form-control" name="description" rows="3" required=""></textarea>
                                        				    <span class="material-input"></span>
                                        				</div>
                                        			</div>
                                        			<div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group label-floating is-select">
                                                            <label class="control-label"><?php echo getEduAppGTLang('method');?></label>
                                                            <div class="select">
                                                                <select name="method" required="">
                                                                    <option value=""><?php echo getEduAppGTLang('select');?></option>
                                                                    <option value="3"><?php echo getEduAppGTLang('card');?></option>
                                        					        <option value="1"><?php echo getEduAppGTLang('cash');?></option>
                                        					        <option value="2"><?php echo getEduAppGTLang('check');?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div><br>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="col-sm-6">
                                            <div class="element-box lined-success shadow">
                                    		    <h5 class="form-header"><?php echo getEduAppGTLang('students');?></h5><br>
		                                        <div class="row">
		                                            <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group label-floating is-select">
                                                            <label class="control-label"><?php echo getEduAppGTLang('status');?></label>
                                                            <div class="select">
                                                                <select name="status" required="">
                                                                    <option value=""><?php echo getEduAppGTLang('select');?></option>
                                                                    <option value="completed"><?php echo getEduAppGTLang('completed');?></option>
					                                                <option value="pending"><?php echo getEduAppGTLang('pending');?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    	                                        <div id="student_selection_holder_mass"></div>
    	                                        <hr>
    	                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <button class="btn btn-success btn-rounded" type="submit"><?php echo getEduAppGTLang('Create_Receipt');?></button>
                                                </div>
                                            </div>
		                                </div>
		                            </div>
		                        <?php echo form_close();?>
	                        </div>
          	            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        function get_class_sections(class_id) 
        {
            $.ajax({
                url: '<?php echo base_url();?>admin/get_class_section/' + class_id ,
                success: function(response)
                {
                    jQuery('#section_holder').html(response);
                }
            });
        }
    </script>
    <script type="text/javascript">
    	function select() 
        {
    		var chk = $('.check');
    		for (i = 0; i < chk.length; i++) 
            {
    			chk[i].checked = true ;
    		}
    	}
    	function unselect() 
        {
    		var chk = $('.check');
    		for (i = 0; i < chk.length; i++) {
    			chk[i].checked = false ;
    		}
    	}
    
        var class_id = '';
        function get_class_students_mass(class_id) {
        	if (class_id !== '') {
            $.ajax({
                url: '<?php echo base_url();?>admin/get_class_students_mass/' + class_id ,
                success: function(response)
                {
                    jQuery('#student_selection_holder_mass').html(response);
                }
            });
          }
        }
        function check_validation(){
            if (class_id !== '') {
                $('.submit').removeAttr('disabled');
            }
            else{
                $('.submit').attr('disabled', 'disabled');
            }
        }
        $('.class_id').change(function(){
            class_id = $('.class_id').val();
            check_validation();
        });
    </script>