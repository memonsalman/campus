    <?php $running_year = $this->crud->getInfo('running_year'); ?>
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
                            <a class="navs-links active" href="<?php echo base_url();?>accountant/students_payments/"><i class="os-icon picons-thin-icon-thin-0426_money_payment_dollars_coins_cash"></i><span><?php echo getEduAppGTLang('Receipts');?></span></a>
                        </li>
                        <li class="navs-item">
                            <a class="navs-links" href="<?php echo base_url();?>accountant/expense/"><i class="os-icon picons-thin-icon-thin-0420_money_cash_coins_payment_dollars"></i><span><?php echo getEduAppGTLang('Expenses');?></span></a>
                        </li>
                    </ul>
                </div>
            </div><br>
            <div class="content-i"> 
                <div class="content-box">
	                <div class="element-wrapper">
		                <div class="tab-content">
		                    <div class="tab-pane active" id="invoices">
		                        <a class="btn btn-rounded btn-success text-white" href="<?php echo base_url();?>accountant/new_payment/"><?php echo getEduAppGTLang('New_Receipt');?></a><br><br>
		                        <div class="element-wrapper">
                                    <div class="element-box-tp">
                                        <div class="table-responsive">
                                            <table class="table table-padded">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo getEduAppGTLang('student');?></th>
                                                        <th><?php echo getEduAppGTLang('class');?></th>
                                                        <th><?php echo getEduAppGTLang('title');?></th>
                                                        <th><?php echo getEduAppGTLang('Individual_Amount');?></th>
                                                        <th><?php echo getEduAppGTLang('Total');?></th>
                                                        <th><?php echo getEduAppGTLang('date');?></th>
                                                        <th><?php echo getEduAppGTLang('options');?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $count = 1;
                                                    $this->db->where('year' , $running_year);
                                                    $this->db->order_by('invoice_id' , 'desc');
                                                    $invoices = $this->db->get('invoice')->result_array();
                                                    foreach($invoices as $row): ?>
                                                        <tr>
                                                            <td class="cell-with-media">
                                                                <img alt="" src="<?php echo $this->crud->get_image_url('student', $row['student_id']);?>" style="height: 25px;"><span> <?php echo $this->crud->get_name('student', $row['student_id']);?></span>
                                                            </td>
                                                            <td>
                                                            <?php echo $this->crud->get_student_year('student', $row['student_id']);?> - 
                                                            <?php echo $this->crud->get_type_name_by_id('class',$row['class_id']);?></td>
                                                            <td><?php echo $row['title'];?></td>
                                                            <td>
                                                                <a class="badge badge-primary" href="javascript:void(0);">
                                                                    <?php echo $this->db->get_where('settings' , array('type'=>'currency'))->row()->description; ?><?php echo $row['amount'];?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <?php 
    																$titles = explode(',',$row['title']);
    																$amounts = explode(',',$row['amount']);
    																$i=1;
    																$total=0;
    																foreach($titles as $key=>$ti){$i++; $total = $total+$amounts[$key];}
    																echo $this->db->get_where('settings' , array('type'=>'currency'))->row()->description . $total;
    															?>
    														</td>
                                                            <td><span><?php echo $row['creation_timestamp'];?></span></td>
                                                            <td class="bolder">
                                                                <center>
                                                                    <a href="<?php echo base_url();?>accountant/invoice_details/<?php echo $row['invoice_id'];?>/" style="color:grey;"><i style="font-size:20px;" class="picons-thin-icon-thin-0424_money_payment_dollar_cash" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo getEduAppGTLang('view_receipt');?>"></i></a>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>