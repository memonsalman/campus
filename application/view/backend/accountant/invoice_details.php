f<?php 
	$invoice_info = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->result_array();
	foreach($invoice_info as $row):
?>
    <div class="content-w" > 
        <?php include 'fancy.php';?>
        <div class="header-spacer"></div> 
        <div class="conty" >
            <div class="content-i">
	            <div class="content-box">
	                <div class="element-wrapper">
	                    <a href="<?php echo base_url();?>accountant/students_payments/" class="btn btn-rounded btn-info"><i class="picons-thin-icon-thin-0131_arrow_back_undo"></i> <?php echo getEduAppGTLang('return');?></a>
	                    <button type="button" class="btn btn-rounded btn-success" onclick="Print('invoiceid')"><?php echo getEduAppGTLang('Print_Receipt');?></button>
                        <div class="container" id="invoiceid">
                            <style>
                                body{margin-top:2px;
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
                                    margin-bottom: 2px
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
                                    font-size: 1.4em
                                }
                                .invoice table .qty,
                                .invoice table .total,
                                .invoice table .unit {
                                    text-align: right;
                                    font-size: 1.4em
                                }
                                .invoice table .no {
                                    color: #fff;
                                    font-size: 1.8em;
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
                                        bottom: 2px;
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
                                    padding: 3px;
                                }
                            </style>
                            <div class="card">
                                <div class="card-body">
                                    <div id="invoice">
                                        <div class="invoice overflow-auto">
                                            <div style="min-width: 600px">
                                                <header>
                                                    <div class="row">
                                                        <div class="col"><img src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>" width="100px" height="100px"></div>
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
                                                            <div class="text-gray-light">RECEIPT TO:</div>
                                                            <h2 class="to"><?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->first_name;?></h2>
                                                            <div class="address">
                                                            <?php
                                                                $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
                                                                $gender = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->sex;
                                                                echo ($gender == 'M' OR $gender == 'Male' ) ? 'S/o' : 'D/o'; echo " ".$this->db->get_where('parent', array('parent_id' => $parent_id))->row()->first_name;
                        									?>                                    
                                                            </div>
                                                            <div class="email">
                                                                <?php echo $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;?> (<?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->stdyear;?> Year)
                                                                <strong><?php echo getEduAppGTLang('roll');?>: <?php echo $this->db->get_where('enroll', array('student_id' => $row['student_id']))->row()->roll;?></strong>
                                                            </div>
                                                        </div>
                                                        <div class="col invoice-details">
                                                            <h5 class="invoice-id">Receipt No: SS<?php echo $row['year'].'/'.$row['invoice_id'];?></h5>
                                                            <h5 class="invoice-id">Date of Receipt: <?php echo $row['creation_timestamp'];?></h5>
                                                        </div>
                                                    </div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-left">Payment Description</th>
                                                                <th colspan="3" class="text-right">TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $titles = explode(',',$row['title']);
                                                            $amounts = explode(',',$row['amount']);
                                                            $i=1;
                                                            $total=0;
                                                            foreach($titles as $key=>$ti){?>
                                                            <tr>
                                                                <td class="no"><?php echo $i;?></td>
                                                                <td colspan="3" class="text-left"><h3><?php echo $ti;?></h3></td>
                                                                <td class="total"><?php echo $this->crud->getInfo('currency'); ?><?php echo $amounts[$key];?></td>
                                                            </tr>
                                                            <?php $i++; $total = $total+$amounts[$key];}?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td colspan="2">GRAND TOTAL</td>
                                                                <td><?php echo $this->crud->getInfo('currency'); ?><?php echo $total;?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <div class="thanks"><br>Thank you!</div>
                                                    <div class="notices">
                                                        <div>Payment Details:-</div>
                                                        <div class="notice">
                                                            <?php if($row['method'] == "2"){?>
                                                                Paid By Cheque (<b>Cheque No:</b> <?php echo $row['chq_number'];?> <b>Bank Name:</b> <?php echo $row['chq_bank_name'];?> <b>Branch:</b> <?php echo $row['chq_bank_branch'];?>)<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }else if($row['method'] == "1"){?>
                                                                Paid By Cash
                                                            <?php }else if($row['method'] == "3"){?>
                                                                Paid By Bank Card<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }else { ?>
                                                                Paid By Online/UPI<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }?>
                                                        </div>
                                                    </div>
													<div class="invoice-footer" style="margin-top: 0px;">
                        			                    <div class="invoice-logo"></div>
                        			                    <div class="invoice-info">
                                                            <center>
                                                                <div class="author-title">
                                                                    <?php echo $row['collected_by'];?>
                                                                </div>
                                                                <br><br>
                                                                <span class="author-subtitle"><?php echo $row['designation'];?></span><br>
                                                            </center>
                                                            <img style="width:25px;height:25px;" src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>"><span><?php echo $this->crud->getInfo('system_name');?></span>
                        			                    </div>
                        		                    </div>
                        		                    <hr style="border-top: 3px dashed green;">
                                                </main>
                                            </div>
                                            <div style="min-width: 600px">
                                                <header>
                                                    <div class="row">
                                                        <div class="col"><img src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>" width="100px" height="100px"></div>
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
                                                            <div class="text-gray-light">RECEIPT TO:</div>
                                                            <h2 class="to"><?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->first_name;?></h2>
                                                            <div class="address">
                                                            <?php
                                                                $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
                                                                $gender = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->sex;
                                                                echo ($gender == 'M' OR $gender == 'Male' ) ? 'S/o' : 'D/o'; echo " ".$this->db->get_where('parent', array('parent_id' => $parent_id))->row()->first_name;
                        									?>                                    
                                                            </div>
                                                            <div class="email">
                                                                <?php echo $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;?> (<?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->stdyear;?> Year)
                                                                <strong><?php echo getEduAppGTLang('roll');?>: <?php echo $this->db->get_where('enroll', array('student_id' => $row['student_id']))->row()->roll;?></strong>
                                                            </div>
                                                        </div>
                                                        <div class="col invoice-details">
                                                            <h5 class="invoice-id">Receipt No: SS<?php echo $row['year'].'/'.$row['invoice_id'];?></h5>
                                                            <h5 class="invoice-id">Date of Receipt: <?php echo $row['creation_timestamp'];?></h5>
                                                        </div>
                                                    </div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-left">Payment Description</th>
                                                                <th colspan="3" class="text-right">TOTAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $titles = explode(',',$row['title']);
                                                            $amounts = explode(',',$row['amount']);
                                                            $i=1;
                                                            $total=0;
                                                            foreach($titles as $key=>$ti){?>
                                                            <tr>
                                                                <td class="no"><?php echo $i;?></td>
                                                                <td colspan="3" class="text-left"><h3><?php echo $ti;?></h3></td>
                                                                <td class="total"><?php echo $this->crud->getInfo('currency'); ?><?php echo $amounts[$key];?></td>
                                                            </tr>
                                                            <?php $i++; $total = $total+$amounts[$key];}?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td colspan="2">GRAND TOTAL</td>
                                                                <td><?php echo $this->crud->getInfo('currency'); ?><?php echo $total;?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <div class="thanks"><br>Thank you!</div>
                                                    <div class="notices">
                                                        <div>Payment Details:-</div>
                                                        <div class="notice">
                                                            <?php if($row['method'] == "2"){?>
                                                                Paid By Cheque (<b>Cheque No:</b> <?php echo $row['chq_number'];?> <b>Bank Name:</b> <?php echo $row['chq_bank_name'];?> <b>Branch:</b> <?php echo $row['chq_bank_branch'];?>)<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }else if($row['method'] == "1"){?>
                                                                Paid By Cash
                                                            <?php }else if($row['method'] == "3"){?>
                                                                Paid By Bank Card<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }else { ?>
                                                                Paid By Online/UPI<br>
                                                                Reference ID : <?php echo $row['reference'];?>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                    <div class="invoice-footer" style="margin-top: 0px;">
                        			                    <div class="invoice-logo">
                        			                        
                        			                    </div>
                        			                    <div class="invoice-info">
                                                            <center>
                                                                <div class="author-title">
                                                                    <?php echo $row['collected_by'];?>
                                                                </div>
                                                                <br><br>
                                                                <span class="author-subtitle"><?php echo $row['designation'];?></span><br>
                                                            </center>
                                                            <img style="width:25px;height:25px;" src="<?php echo base_url();?>public/uploads/<?php echo $this->crud->getInfo('logo');?>"><span><?php echo $this->crud->getInfo('system_name');?></span>
                        			                    </div>
                        		                    </div>
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
	            </div>
            </div>
        </div>
    </div>
<?php endforeach;?>
    <script>
        function Print(div) 
        {
             var printContents = document.getElementById(div).innerHTML;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = printContents;
        }
</script>
