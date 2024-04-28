<?php	

class booking extends common
{
	public function __construct()
	{  
		parent::__construct(); 
	}
	
	public function listview($postArr)
	{  
		  $bindArr=array();
		  
		  $sess_log_superuser = $this->sess_log_superuser; 
		  $sess_sales_team_access_ids = $this->sess_sales_team_access_ids; 
		  if($sess_sales_team_access_ids=="") $sess_sales_team_access_ids=0;
		 
		  $salesTeamfilt = " and bk.sales_team in ($sess_sales_team_access_ids) ";
		  if($sess_log_superuser) { $salesTeamfilt = "";  } 
		  
		  $draw = $postArr['draw'];
		  $start = ($postArr['start'])?$postArr['start']:0;
		  $limit = ($postArr['length'])?$postArr['length']:0;
		  
		  $start=(int) $start; 
		  $limit=(int) $limit;
		  
		  
		  $search = ($postArr['search']['value']);	  
		  $where = "";
		  if($search)
		  {
		  		$query_val = "%".$search."%"; 
				$where = 'and (bk.order_no like :search_str or bk.customer_name like :search_str or bk.customer_mobile like :search_str)'; 
				$bindArr[':search_str']=array("value"=>$query_val,"type"=>"text");
		  }
		  $ordstatus_filtval = $postArr['ordstatus_filt'];	
		  $ordstatus_filter="";
		  if($ordstatus_filtval){ $ordstatus_filter=" and bk.order_status=:statusfilt"; $bindArr[':statusfilt']=array("value"=>$ordstatus_filtval,"type"=>"int"); }
		  
		  $tot_sql="select count(*) as cnt from srt_booking_transaction as bk where bk.is_deleted<>1 $ordstatus_filter $salesTeamfilt $where";
		  $rs_total = $this->pdoObj->fetchSingle($tot_sql, $bindArr); 
		  $totalRows=($rs_total["cnt"])?$rs_total["cnt"]:0;
		  
		  //$sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price, coalesce(total_tata,0)+coalesce(total_srt,0) as total_offer, coalesce(sum(rcp.receipt_amount),0) as bk_amount_received  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1)  where bk.is_deleted<>1 $where $ordstatus_filter group by bk.booking_transaction_id order by order_date "; 
		   $sql="select bk.booking_transaction_id, bk.order_no, bk.order_date , ordsts.orderstatus_name, bk.customer_name, bk.customer_mobile, prd.productline_name, sls.sales_team_name, bk.onroad_price,coalesce(total_tata,0)+coalesce(total_srt,0)+coalesce(total_srt_addition,0) as total_offer, ca.employee_name as customer_advisor_name, coalesce(total_srt_addition,0) as total_srt_addition, case finance when 1 then 'In house' when 2 then 'Customer' else '' end as insurance_type_desc, coalesce(total_srt,0) as total_srt, bk.order_status, bk.off_acc_approved_status, bk.off_admin_approved_status , coalesce(sum(rcp.receipt_amount),0) as bk_amount_received  from srt_booking_transaction as bk left join srt_orderstatus_master as ordsts on bk.order_status = ordsts.orderstatus_id left join srt_productline_master as prd on bk.product_line=prd.productline_id left join srt_sales_team_master as sls on bk.sales_team=sls.sales_team_id left join srt_receipts_transaction as rcp on (bk.booking_transaction_id=rcp.booking_transaction_id and amount_reveived_status=1 and rcp.is_deleted<>1) left join srt_employee_master as ca on bk.customer_advisor=ca.employee_id  where bk.is_deleted<>1 $where $ordstatus_filter $salesTeamfilt group by bk.booking_transaction_id  order by order_date "; 
			$sql.="LIMIT :limitstart_val, :limitend_val ";
			$bindArr[':limitstart_val'] = array("value"=>$start,"type"=>"int");
			$bindArr[':limitend_val']=array("value"=>$limit,"type"=>"int");
			$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
			
			
			$sendRs=array();
			
			$rsCnt=0;
			$PageSno=$start;
			foreach($recs as $rs)
			{
				$cid=$this->purifyString($rs["booking_transaction_id"]);
				$order_no=$this->purifyString($rs["order_no"]);
				$order_date=$this->convertDate($this->purifyString($rs["order_date"]));
				$orderstatus_name=$this->purifyString($rs["orderstatus_name"]);
				$customer_name = $this->purifyString($rs["customer_name"]);
				$customer_mobile = $this->purifyString($rs["customer_mobile"]);
				$productline_name = $this->purifyString($rs["productline_name"]); 
				$sales_team_name = $this->purifyString($rs["sales_team_name"]);
				$customer_advisor_name = $this->purifyString($rs["customer_advisor_name"]);
				$insurance_type_desc = $this->purifyString($rs["insurance_type_desc"]);
				
				$onroad_price = number_format($this->purifyString($rs["onroad_price"]),2);
				$total_offer =  number_format($this->purifyString($rs["total_offer"]),2);
				$bk_amount_received =  number_format($this->purifyString($rs["bk_amount_received"]),2);
				$total_srt_addition =  number_format($this->purifyString($rs["total_srt_addition"]),2);
				
				$remark_desc="";
				if($rs["order_status"]=="1")
				{
					if($rs["bk_amount_received"]<=0) $remark_desc="Payment pending";
					
					if(($rs["total_srt"]+$rs["total_srt_addition"])>0)
					{
						if($rs["off_acc_approved_status"]==1 or $rs["off_admin_approved_status"]==1){ }
						else 
						{ 
							if($remark_desc) $remark_desc.=", ";  
							
							$remark_desc.="Approval pending"; 
						}
					}
				}
				
				
				// <span class="delete act-delete"  onclick="viewDeleteBookingMaster('.$cid.');"><i class="fa fa-trash-o"></i> Delete</span> // delete will be inside addedit page
				//$sendRs[$rsCnt]=array("booking_transaction_id"=>$cid,"booking_name"=>$cname,"status"=>$cstatus,"status_desc"=>$cstatus_desc);
				$sendRs[$rsCnt]=array($PageSno+1, $order_date, $orderstatus_name, $sales_team_name, $customer_advisor_name, $customer_name, $customer_mobile, $productline_name, $onroad_price, $total_offer, $total_srt_addition, $insurance_type_desc, $remark_desc, '<span class="edit js-open-modal act-edit" data-modal-id="popup1" onclick="CreateUpdateBookingMasterList('.$cid.');"><i class="fa fa-edit"></i> View </span>');
				$rsCnt++;
				$PageSno++;
				
			}
			
			
			//$sendArr=array('rsData'=>$sendRs,'status'=>'success'); 
			$sendArr=array('data'=>$sendRs, 'draw'=>$draw, 'recordsFiltered'=>$totalRows , 'recordsTotal'=>$totalRows); 
			
			return json_encode($sendArr);
	}
	
	public function getSingleView($postArr)
	{
			$getcid=$this->purifyInsertString($postArr["id"]);
			
			$sql="select bk.*, ft.finance_process_status from srt_booking_transaction as bk left join srt_finance_transaction as ft on bk.booking_transaction_id=ft.booking_transaction_id where bk.booking_transaction_id=:booking_transaction_id";
			$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
			$recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		 
			$cid=$this->purifyString($recs["booking_transaction_id"]);
		
			
			$productline_id = $this->purifyString($recs["product_line"]);
			$sales_team_id = $this->purifyString($recs["sales_team"]);
			$order_status_id = $this->purifyString($recs["order_status"]);
			$parent_productline_id = $this->purifyString($recs["parent_product_line"]);
			$productcolour1_id = $this->purifyString($recs["product_color_primary"]);
			$productcolour2_id = $this->purifyString($recs["product_color_secondary"]);
			$productcolour3_id = $this->purifyString($recs["product_color_additional"]);
			$source_contact_id = $this->purifyString($recs["source_contact"]);
			
			$recs['order_date'] = $this->convertDate($recs["order_date"]);
			$recs['nominee_dob'] = $this->convertDate($recs["nominee_dob"]);
			$recs['dob'] = $this->convertDate($recs["dob"]);
			$recs['edd'] = $this->convertDate($recs["edd"]);
			$recs['revised_edd'] = $this->convertDate($recs["revised_edd"]);
			
			if(!$cid) 
			{
				$recs['order_date'] = $this->convertDate(date('Y-m-d'));
				$order_status_id=1; $recs['order_status']=1;
			}
			
			$productline = $this->getModuleComboList('productline', $productline_id);
			$sales_team = $this->getModuleComboList('sales_team', $sales_team_id);
			$parent_product_line = $this->getModuleComboList('parent_productline', $parent_productline_id);
			$productcolour1 = $this->getModuleComboList('productcolour', $productcolour1_id);
			$productcolour2 = $this->getModuleComboList('productcolour', $productcolour2_id);
			$productcolour3 = $this->getModuleComboList('productcolour', $productcolour3_id);
			$order_statuslist = $this->getModuleComboList('order_status', $order_status_id, 'onlythisrec');
			$source_of_contactlist = $this->getModuleComboList('source_of_contact', $source_contact_id);
			$customer_advisorlist = $this->getModuleComboList('user', $source_contact_id);
			
			
			
			$sendRs=$recs; 
			
			$combo_list = array('sales_team'=>$sales_team, 'productline'=>$productline, 'parent_product_line'=>$parent_product_line, 'productcolour1'=>$productcolour1, 'productcolour2'=>$productcolour2, 'productcolour3'=>$productcolour3, 'order_statuslist'=>$order_statuslist, 'source_of_contactlist'=>$source_of_contactlist, 'customer_advisorlist'=>$customer_advisorlist);
			
			$sendArr=array('rsData'=>$sendRs, 'combo_list'=>$combo_list, 'status'=>'success');  
			return $sendArr;
			//return json_encode($sendArr);
	}
	
	public function saveprocess($post_arr)
	{
		
		
		$id=$this->purifyInsertString($post_arr["hid_id"]);
		$get_arr['order_no']=$this->htmpurify->purifier->purify($post_arr['order_no']);
		$get_arr['order_date']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['order_date']));
		$get_arr['order_status']=$this->htmpurify->purifier->purify($post_arr['order_status']);
		$get_arr['sales_team']=$this->htmpurify->purifier->purify($post_arr['sales_team']);
		$get_arr['customer_advisor']=$this->htmpurify->purifier->purify($post_arr['customer_advisor']);
		$get_arr['source_contact']=$this->htmpurify->purifier->purify($post_arr['source_contact']);
		$get_arr['customer_name']=$this->htmpurify->purifier->purify($post_arr['customer_name']);
		$get_arr['customer_mobile']=$this->htmpurify->purifier->purify($post_arr['customer_mobile']);
		$get_arr['customer_pan']=$this->htmpurify->purifier->purify($post_arr['customer_pan']);
		$get_arr['dob']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['dob']));
		$get_arr['nominee_name']=$this->htmpurify->purifier->purify($post_arr['nominee_name']);
		$get_arr['nominee_dob']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['nominee_dob']));
		$get_arr['city']=$this->htmpurify->purifier->purify($post_arr['city']);
		$get_arr['area']=$this->htmpurify->purifier->purify($post_arr['area']);
		$get_arr['pincode']=$this->htmpurify->purifier->purify($post_arr['pincode']);
		$get_arr['corporate_name']=$this->htmpurify->purifier->purify($post_arr['corporate_name']);
		$get_arr['ex_vechicle']=$this->htmpurify->purifier->purify($post_arr['ex_vechicle']);
		$get_arr['customer_address']=$this->htmpurify->purifier->purify($post_arr['customer_address']);
		$get_arr['parent_product_line']=$this->htmpurify->purifier->purify($post_arr['parent_product_line']);
		$get_arr['product_line']=$this->htmpurify->purifier->purify($post_arr['product_line']);
		$get_arr['vehicle_type']=$this->htmpurify->purifier->purify($post_arr['vehicle_type']);
		$get_arr['product_color_primary']=$this->htmpurify->purifier->purify($post_arr['product_color_primary']);
		$get_arr['product_color_secondary']=$this->htmpurify->purifier->purify($post_arr['product_color_secondary']);
		$get_arr['finance']=$this->htmpurify->purifier->purify($post_arr['finance']);
		$get_arr['product_color_additional']=$this->htmpurify->purifier->purify($post_arr['product_color_additional']);
		$get_arr['opportunity_id']=$this->htmpurify->purifier->purify($post_arr['opportunity_id']);
		$get_arr['insurance_type']=$this->htmpurify->purifier->purify($post_arr['insurance_type']);
		$get_arr['edd']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['edd']));
		$get_arr['revised_edd']=$this->convertDate($this->htmpurify->purifier->purify($post_arr['revised_edd']));
		$get_arr['insurance_detail']=$this->htmpurify->purifier->purify($post_arr['insurance_detail']);
		$get_arr['remarks']=$this->htmpurify->purifier->purify($post_arr['remarks']);
		$get_arr['registration_type']=$this->htmpurify->purifier->purify($post_arr['registration_type']);
		$get_arr['ex_showroom_price']=$this->htmpurify->purifier->purify($post_arr['ex_showroom_price']);
		$get_arr['insurance_method']=$this->htmpurify->purifier->purify($post_arr['insurance_method']);
		$get_arr['rto_fee']=$this->htmpurify->purifier->purify($post_arr['rto_fee']);
		$get_arr['taxi_charges']=$this->htmpurify->purifier->purify($post_arr['taxi_charges']);
		$get_arr['accessories']=$this->htmpurify->purifier->purify($post_arr['accessories']);
		$get_arr['amc']=$this->htmpurify->purifier->purify($post_arr['amc']);
		$get_arr['ex_price']=$this->htmpurify->purifier->purify($post_arr['ex_price']);
		$get_arr['onroad_price']=$this->htmpurify->purifier->purify($post_arr['onroad_price']);
		$get_arr['cosumer_offer']=$this->htmpurify->purifier->purify($post_arr['cosumer_offer']);
		$get_arr['cosumer_offer_srt']=$this->htmpurify->purifier->purify($post_arr['cosumer_offer_srt']);
		$get_arr['corporate_offer']=$this->htmpurify->purifier->purify($post_arr['corporate_offer']);
		$get_arr['corporate_offer_srt']=$this->htmpurify->purifier->purify($post_arr['corporate_offer_srt']);
		$get_arr['exchange_offer']=$this->htmpurify->purifier->purify($post_arr['exchange_offer']);
		$get_arr['exchange_offer_srt']=$this->htmpurify->purifier->purify($post_arr['exchange_offer_srt']);
		$get_arr['access_offer']=$this->htmpurify->purifier->purify($post_arr['access_offer']);
		$get_arr['access_offer_srt']=$this->htmpurify->purifier->purify($post_arr['access_offer_srt']);
		$get_arr['insurance_offer']=$this->htmpurify->purifier->purify($post_arr['insurance_offer']);
		$get_arr['insurance_offer_srt']=$this->htmpurify->purifier->purify($post_arr['insurance_offer_srt']);
		$get_arr['add_discount']=$this->htmpurify->purifier->purify($post_arr['add_discount']);
		$get_arr['add_discount_srt']=$this->htmpurify->purifier->purify($post_arr['add_discount_srt']);
		$get_arr['edr']=$this->htmpurify->purifier->purify($post_arr['edr']);
		$get_arr['edr_srt']=$this->htmpurify->purifier->purify($post_arr['edr_srt']);
		$get_arr['other_offer_desc']=$this->htmpurify->purifier->purify($post_arr['other_offer_desc']);
		$get_arr['other_contribution']=$this->htmpurify->purifier->purify($post_arr['other_contribution']);
		$get_arr['other_contribution_srt']=$this->htmpurify->purifier->purify($post_arr['other_contribution_srt']);
		$get_arr['total_tata']=$this->htmpurify->purifier->purify($post_arr['total_tata']);
		$get_arr['total_srt']=$this->htmpurify->purifier->purify($post_arr['total_srt']);
		
		
		$get_arr['customer_alternate_no']=$this->htmpurify->purifier->purify($post_arr['customer_alternate_no']);
		$get_arr['customer_email']=$this->htmpurify->purifier->purify($post_arr['customer_email']);
		$get_arr['nominee_age']=$this->htmpurify->purifier->purify($post_arr['nominee_age']);
		$get_arr['corporate_type']=$this->htmpurify->purifier->purify($post_arr['corporate_type']);
		$get_arr['cosumer_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['cosumer_offer_srt_addition']);
		$get_arr['corporate_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['corporate_offer_srt_addition']);
		$get_arr['exchange_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['exchange_offer_srt_addition']);
		$get_arr['access_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['access_offer_srt_addition']);
		$get_arr['insurance_offer_srt_addition']=$this->htmpurify->purifier->purify($post_arr['insurance_offer_srt_addition']);
		$get_arr['add_discount_srt_addition']=$this->htmpurify->purifier->purify($post_arr['add_discount_srt_addition']);
		$get_arr['edr_srt_addition']=$this->htmpurify->purifier->purify($post_arr['edr_srt_addition']);
		$get_arr['other_contribution_srt_addition']=$this->htmpurify->purifier->purify($post_arr['other_contribution_srt_addition']);
		$get_arr['total_srt_addition']=$this->htmpurify->purifier->purify($post_arr['total_srt_addition']);
		$get_arr['offer_remarks']=$this->htmpurify->purifier->purify($post_arr['offer_remarks']);
		
		
		
		$cnt_ext_sql="select count(*) as ext_cnt from srt_booking_transaction where booking_transaction_id=:booking_transaction_id "; 
		$bindExtCntArr=array(":booking_transaction_id"=>array("value"=>$id,"type"=>"int"));
		$rs_qry_exts = $this->pdoObj->fetchSingle($cnt_ext_sql, $bindExtCntArr); 
		$ext_cnt_val=$rs_qry_exts["ext_cnt"];
		
		$ins=" srt_booking_transaction SET booking_transaction_id=:booking_transaction_id, order_no=:order_no, order_date=:order_date, order_status=:order_status, sales_team=:sales_team, customer_advisor=:customer_advisor, source_contact=:source_contact, customer_name=:customer_name, customer_mobile=:customer_mobile, customer_pan=:customer_pan, dob=:dob, nominee_name=:nominee_name, nominee_dob=:nominee_dob, city=:city, area=:area, pincode=:pincode, corporate_name=:corporate_name, ex_vechicle=:ex_vechicle, customer_address=:customer_address, parent_product_line=:parent_product_line, product_line=:product_line, vehicle_type=:vehicle_type, product_color_primary=:product_color_primary, product_color_secondary=:product_color_secondary, finance=:finance, product_color_additional=:product_color_additional, opportunity_id=:opportunity_id, insurance_type=:insurance_type, edd=:edd, revised_edd=:revised_edd, insurance_detail=:insurance_detail, remarks=:remarks, registration_type=:registration_type, ex_showroom_price=:ex_showroom_price, insurance_method=:insurance_method, rto_fee=:rto_fee, taxi_charges=:taxi_charges, accessories=:accessories, amc=:amc, ex_price=:ex_price, onroad_price=:onroad_price, cosumer_offer=:cosumer_offer, cosumer_offer_srt=:cosumer_offer_srt, corporate_offer=:corporate_offer, corporate_offer_srt=:corporate_offer_srt, exchange_offer=:exchange_offer, exchange_offer_srt=:exchange_offer_srt, access_offer=:access_offer, access_offer_srt=:access_offer_srt, insurance_offer=:insurance_offer, insurance_offer_srt=:insurance_offer_srt, add_discount=:add_discount, add_discount_srt=:add_discount_srt, edr=:edr, edr_srt=:edr_srt, other_offer_desc=:other_offer_desc, other_contribution=:other_contribution, other_contribution_srt=:other_contribution_srt, total_tata=:total_tata, total_srt=:total_srt, 
		
		customer_alternate_no=:customer_alternate_no, customer_email=:customer_email, nominee_age=:nominee_age, corporate_type=:corporate_type, cosumer_offer_srt_addition=:cosumer_offer_srt_addition, corporate_offer_srt_addition=:corporate_offer_srt_addition, exchange_offer_srt_addition=:exchange_offer_srt_addition, access_offer_srt_addition=:access_offer_srt_addition, insurance_offer_srt_addition=:insurance_offer_srt_addition, add_discount_srt_addition=:add_discount_srt_addition, edr_srt_addition=:edr_srt_addition, other_contribution_srt_addition=:other_contribution_srt_addition, total_srt_addition=:total_srt_addition, offer_remarks=:offer_remarks ";
		
		
		$insBind=array( ":booking_transaction_id"=>array("value"=>$get_arr['booking_transaction_id'],"dtype"=>"int"), ":order_no"=>array("value"=>$get_arr['order_no'],"dtype"=>"text"), ":order_date"=>array("value"=>$get_arr['order_date'],"dtype"=>"text"), ":order_status"=>array("value"=>$get_arr['order_status'],"dtype"=>"int"), ":sales_team"=>array("value"=>$get_arr['sales_team'],"dtype"=>"int"), ":customer_advisor"=>array("value"=>$get_arr['customer_advisor'],"dtype"=>"int"), ":source_contact"=>array("value"=>$get_arr['source_contact'],"dtype"=>"int"), ":customer_name"=>array("value"=>$get_arr['customer_name'],"dtype"=>"text"), ":customer_mobile"=>array("value"=>$get_arr['customer_mobile'],"dtype"=>"text"), ":customer_pan"=>array("value"=>$get_arr['customer_pan'],"dtype"=>"text"), ":dob"=>array("value"=>$get_arr['dob'],"dtype"=>"text"), ":nominee_name"=>array("value"=>$get_arr['nominee_name'],"dtype"=>"text"), ":nominee_dob"=>array("value"=>$get_arr['nominee_dob'],"dtype"=>"text"), ":city"=>array("value"=>$get_arr['city'],"dtype"=>"text"), ":area"=>array("value"=>$get_arr['area'],"dtype"=>"text"), ":pincode"=>array("value"=>$get_arr['pincode'],"dtype"=>"text"), ":corporate_name"=>array("value"=>$get_arr['corporate_name'],"dtype"=>"text"), ":ex_vechicle"=>array("value"=>$get_arr['ex_vechicle'],"dtype"=>"int"), ":customer_address"=>array("value"=>$get_arr['customer_address'],"dtype"=>"text"), ":parent_product_line"=>array("value"=>$get_arr['parent_product_line'],"dtype"=>"int"), ":product_line"=>array("value"=>$get_arr['product_line'],"dtype"=>"int"), ":vehicle_type"=>array("value"=>$get_arr['vehicle_type'],"dtype"=>"int"), ":product_color_primary"=>array("value"=>$get_arr['product_color_primary'],"dtype"=>"int"), ":product_color_secondary"=>array("value"=>$get_arr['product_color_secondary'],"dtype"=>"int"), ":finance"=>array("value"=>$get_arr['finance'],"dtype"=>"int"), ":product_color_additional"=>array("value"=>$get_arr['product_color_additional'],"dtype"=>"int"), ":opportunity_id"=>array("value"=>$get_arr['opportunity_id'],"dtype"=>"text"), ":insurance_type"=>array("value"=>$get_arr['insurance_type'],"dtype"=>"int"), ":edd"=>array("value"=>$get_arr['edd'],"dtype"=>"text"), ":revised_edd"=>array("value"=>$get_arr['revised_edd'],"dtype"=>"text"), ":insurance_detail"=>array("value"=>$get_arr['insurance_detail'],"dtype"=>"int"), ":remarks"=>array("value"=>$get_arr['remarks'],"dtype"=>"text"), ":registration_type"=>array("value"=>$get_arr['registration_type'],"dtype"=>"int"), ":ex_showroom_price"=>array("value"=>$get_arr['ex_showroom_price'],"dtype"=>"text"), ":insurance_method"=>array("value"=>$get_arr['insurance_method'],"dtype"=>"text"), ":rto_fee"=>array("value"=>$get_arr['rto_fee'],"dtype"=>"text"), ":taxi_charges"=>array("value"=>$get_arr['taxi_charges'],"dtype"=>"text"), ":accessories"=>array("value"=>$get_arr['accessories'],"dtype"=>"text"), ":amc"=>array("value"=>$get_arr['amc'],"dtype"=>"text"), ":ex_price"=>array("value"=>$get_arr['ex_price'],"dtype"=>"text"), ":onroad_price"=>array("value"=>$get_arr['onroad_price'],"dtype"=>"text"), ":cosumer_offer"=>array("value"=>$get_arr['cosumer_offer'],"dtype"=>"text"), ":cosumer_offer_srt"=>array("value"=>$get_arr['cosumer_offer_srt'],"dtype"=>"text"), ":corporate_offer"=>array("value"=>$get_arr['corporate_offer'],"dtype"=>"text"), ":corporate_offer_srt"=>array("value"=>$get_arr['corporate_offer_srt'],"dtype"=>"text"), ":exchange_offer"=>array("value"=>$get_arr['exchange_offer'],"dtype"=>"text"), ":exchange_offer_srt"=>array("value"=>$get_arr['exchange_offer_srt'],"dtype"=>"text"), ":access_offer"=>array("value"=>$get_arr['access_offer'],"dtype"=>"text"), ":access_offer_srt"=>array("value"=>$get_arr['access_offer_srt'],"dtype"=>"text"), ":insurance_offer"=>array("value"=>$get_arr['insurance_offer'],"dtype"=>"text"), ":insurance_offer_srt"=>array("value"=>$get_arr['insurance_offer_srt'],"dtype"=>"text"), ":add_discount"=>array("value"=>$get_arr['add_discount'],"dtype"=>"text"), ":add_discount_srt"=>array("value"=>$get_arr['add_discount_srt'],"dtype"=>"text"), ":edr"=>array("value"=>$get_arr['edr'],"dtype"=>"text"), ":edr_srt"=>array("value"=>$get_arr['edr_srt'],"dtype"=>"text"), ":other_offer_desc"=>array("value"=>$get_arr['other_offer_desc'],"dtype"=>"text"), ":other_contribution"=>array("value"=>$get_arr['other_contribution'],"dtype"=>"text"), ":other_contribution_srt"=>array("value"=>$get_arr['other_contribution_srt'],"dtype"=>"text"), ":total_tata"=>array("value"=>$get_arr['total_tata'],"dtype"=>"text"), ":total_srt"=>array("value"=>$get_arr['total_srt'],"dtype"=>"text"), ":customer_alternate_no"=>array("value"=>$get_arr['customer_alternate_no'],"dtype"=>"text"), ":customer_email"=>array("value"=>$get_arr['customer_email'],"dtype"=>"text"), ":nominee_age"=>array("value"=>$get_arr['nominee_age'],"dtype"=>"text"), ":corporate_type"=>array("value"=>$get_arr['corporate_type'],"dtype"=>"text"), ":cosumer_offer_srt_addition"=>array("value"=>$get_arr['cosumer_offer_srt_addition'],"dtype"=>"text"), ":corporate_offer_srt_addition"=>array("value"=>$get_arr['corporate_offer_srt_addition'],"dtype"=>"text"), ":exchange_offer_srt_addition"=>array("value"=>$get_arr['exchange_offer_srt_addition'],"dtype"=>"text"), ":access_offer_srt_addition"=>array("value"=>$get_arr['access_offer_srt_addition'],"dtype"=>"text"), ":insurance_offer_srt_addition"=>array("value"=>$get_arr['insurance_offer_srt_addition'],"dtype"=>"text"), ":add_discount_srt_addition"=>array("value"=>$get_arr['add_discount_srt_addition'],"dtype"=>"text"), ":edr_srt_addition"=>array("value"=>$get_arr['edr_srt_addition'],"dtype"=>"text"), ":other_contribution_srt_addition"=>array("value"=>$get_arr['other_contribution_srt_addition'],"dtype"=>"text"), ":total_srt_addition"=>array("value"=>$get_arr['total_srt_addition'],"dtype"=>"text"), ":offer_remarks"=>array("value"=>$get_arr['offer_remarks'],"dtype"=>"text")	,':sess_user_id'=>array("value"=>$this->sess_userid,"type"=>"int")); 
		
		$sql_ext_chk = "select count(*) as rec_exist_cnt from srt_booking_transaction where trim(order_no)=:order_no  and is_deleted<>1 ";
		$bindExtChkArr=array(":order_no"=>array("value"=>$get_arr['order_no'],"dtype"=>"text")); 
		
		if($ext_cnt_val>0) 
		{ 
			$strQuery="UPDATE $ins, lastmodifiedon=now(),lastmodifiedby=:sess_user_id where booking_transaction_id=:booking_transaction_id ";
			$insBind[":booking_transaction_id"]=array("value"=>$id,"type"=>"int"); 
			
			$sql_ext_chk .= " and booking_transaction_id<>:booking_transaction_id ";
			$bindExtChkArr[":booking_transaction_id"]=array("value"=>$id,"dtype"=>"int");  
			$opmsg="Booking updated successfully!";
		}
		else
		{
			$strQuery="INSERT INTO $ins, createdon=now(), createdby=:sess_user_id"; 
			
			
			$opmsg="Booking inserted successfully!";
		}
		
		$rs_ext_chk = $this->pdoObj->fetchSingle($sql_ext_chk, $bindExtChkArr);  
		$rec_exist_cnt_val	=	$rs_ext_chk['rec_exist_cnt'];  
		
		$opStatus='failure';
		$opMessage='failure';
		
		if($rec_exist_cnt_val>0) 
		{
			$opMessage='Record already exists'; 
			$opExists='exists';
		} 
		else 
		{
			//echo $strQuery.json_encode($insBind);
			$exec = $this->pdoObj->execute($strQuery, $insBind);
			
			if($exec)
			{
				$opStatus='success';
				$opMessage=$opmsg; 
			} 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus,'rc_exists'=>$opExists);  
		
		return json_encode($sendArr);
	}
	
	public function deleteprocess($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		
		$bindArr=array(); 
		
		
		
		//$strQuery=" delete from srt_booking_transaction $Setup_filt and booking_transaction_id=:booking_transaction_id "; 
		$strQuery=" update srt_booking_transaction set is_deleted=1, deleted_date=now(), deleted_user=:sess_user_id where booking_transaction_id=:id  ";  
		$bindArr[':id']=array("value"=>$id,"type"=>"int");
		$bindArr[':sess_user_id']=array("value"=>$this->sess_userid,"type"=>"int");
		
		$exec = $this->pdoObj->execute($strQuery, $bindArr);
		
		$opStatus='failure';
		$opMessage='failure';
		if($exec)
		{
			$opStatus='success';
			$opMessage='Booking details deleted successfully'; 
		} 
		
		$sendArr=array('message'=>$opMessage,'status'=>$opStatus);  
		
		return json_encode($sendArr);			
	}		
	
	public function comboview($id=0)
	{
		  $bindArr=array();
		  
		  
		  $whereor  ='';
		  
		  if($id>0)
		  { 
			$bindArr[':id']=array("value"=>$id,"type"=>"int");
			$whereor = " and (active_status = 1 or booking_transaction_id=:id)";
		  }
		 
		  $sql="select booking_transaction_id, booking_name, active_status, case active_status when 1 then 'Active' else 'Inactive' end as active_status_desc from srt_booking_transaction WHERE is_deleted<>1  $whereor  order by booking_name ";
		  $recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		  return $recs;
	}
	
	public function deleteRestrition($postArr)
	{
		$id=$this->purifyInsertString($postArr["hid_id"]);
		$cnt_ext_sql="(select count(*) as ext_cnt, 'Product Line referring Sub Booking' as msg from srt_booking_transaction where booking_transaction_id=:id)"; 
		$bindExtCntArr=array(":id"=>array("value"=>$id,"type"=>"int"));
		//$rs_qry_exts = $this->pdoObj->fetchMultiple($cnt_ext_sql, $bindExtCntArr); 
		
		$_cnt = 0;
		$status = 'success';
		$msgArr = array();
		$_msg = 'Do you want to delete?';
		foreach($rs_qry_exts as $rsop)
		{
			if($rsop['ext_cnt']>0)
			{
				$msgArr[] = $rsop['msg'];
				$_cnt++;
			}
		}
		
		if($_cnt>0)
		{
			$status = 'failure';
			//$_msg = implode("\n",$msgArr);
			$_msg = "Booking cannot be deleted";
		}
		$arr = array('status'=>$status, 'message'=>$_msg);
		return json_encode($arr);
	}
	public function getExchangeAmountSingleView($postArr,$sendtype='json')
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		$modtype=$this->purifyInsertString($postArr["modtype"]); 
		
		$sql="select exchange_price from srt_vehicle_exchange where booking_transaction_id=:booking_transaction_id";
		$bindArr=array(":booking_transaction_id"=>array("value"=>$getcid,"type"=>"int"));
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);  
	 
		//$cid=$this->purifyString($recs["booking_transaction_id"]); 
		
		$exchange_price = $this->purifyString($recs["exchange_price"]); 
		
		$sendRs=array('exchange_price'=>$exchange_price); 
		
		$sendArr=array('rsData'=>$sendRs, 'modtype'=>$modtype, 'status'=>'success');  
		
		if($sendtype=='json') return json_encode($sendArr);
		else if($sendtype=='onlyprice') return $exchange_price;
		else return $sendArr;
		 
	}
	public function getQuotationOfferAmountSingleView($postArr,$sendtype='json')
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		$modtype=$this->purifyInsertString($postArr["modtype"]); 
		
		$vehicle_type=$this->purifyInsertString($postArr["vehicle_type"]); 
		$insurance_detail=$this->purifyInsertString($postArr["insurance_detail"]); 
		$parent_product_line=$this->purifyInsertString($postArr["parent_product_line"]); 
		$product_line=$this->purifyInsertString($postArr["product_line"]); 
		$corporate_type=$this->purifyInsertString($postArr["corporate_type"]);   
		$insurance_type=$this->purifyInsertString($postArr["insurance_type"]);    
		$product_color_primary=$this->purifyInsertString($postArr["product_color_primary"]); 
		$registration_type=$this->purifyInsertString($postArr["registration_type"]); 
		
		if(!$product_color_primary) $product_color_primary=0;
		
		$prdlineClrsrchlik="";
		if($product_color_primary) 
		{
			$prdlineClrsrchlik=" and concat(',',product_colour_ids,',') like ('%,$product_color_primary,%') ";
		}  
		
		$sql="select *, (insurance_amount+nill_depriciation_amount) as ins_nill_dep from   srt_price_list_master where parent_productline_id=:parent_productline_id and productline_id=:productline_id and vechile_type=:vechile_type and ( date_format(price_date, '%m-%Y')=date_format(curdate(), '%m-%Y') or price_date<=curdate() ) and registration_type=:registration_type $prdlineClrsrchlik order by price_date desc limit 1";
		$bindArr=array(":parent_productline_id"=>array("value"=>$parent_product_line,"type"=>"int"), ":productline_id"=>array("value"=>$product_line,"type"=>"int"), ":vechile_type"=>array("value"=>$vehicle_type,"type"=>"int"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int"));
		$price_recs = $this->pdoObj->fetchSingle($sql, $bindArr); 
		//echo $sql.json_encode($bindArr);
	 
		//$cid=$this->purifyString($recs["insurance_amount"]); 
		
		if($insurance_type==1)
		{ 
			if($insurance_detail=='2' and $price_recs["nill_depriciation_amount"]!="")
			{
				$price_recs["insurance_amount"]=$price_recs["ins_nill_dep"];  
			}
		}
		else { $price_recs["insurance_amount"]="0.00"; }
		
		
		$offerlist=$this->getOfferAmountSingleView($postArr,'onlyrsarr');
		$price_recs["exchange_price"]=$this->getExchangeAmountSingleView($postArr,'onlyprice');
		 
		
		$sendRs=array('price_list'=>$price_recs, 'offer_list'=>$offerlist); 
		
		$sendArr=array('rsData'=>$sendRs, 'modtype'=>$modtype, 'status'=>'success');  
		
		if($sendtype=='json') return json_encode($sendArr);
		else return $sendArr;
		 
	}
	public function getOfferAmountSingleView($postArr,$sendtype='json')
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		$modtype=$this->purifyInsertString($postArr["modtype"]); 
		
		$vehicle_type=$this->purifyInsertString($postArr["vehicle_type"]); 
		$insurance_detail=$this->purifyInsertString($postArr["insurance_detail"]); 
		$parent_product_line=$this->purifyInsertString($postArr["parent_product_line"]); 
		$product_line=$this->purifyInsertString($postArr["product_line"]);  
		$ex_vechicle=$this->purifyInsertString($postArr["ex_vechicle"]); 
		$corporate_type=$this->purifyInsertString($postArr["corporate_type"]);  
		$insurance_type=$this->purifyInsertString($postArr["insurance_type"]);  
		$product_color_primary=$this->purifyInsertString($postArr["product_color_primary"]);  
		$registration_type=$this->purifyInsertString($postArr["registration_type"]);  
		
		if(!$product_line) $product_line=0;
		if(!$product_color_primary) $product_color_primary=0;
		
		$prdlinesrchlik="('%,$product_line,%')";
		
		$prdlineClrsrchlik="";
		if($product_color_primary) 
		{
			$prdlineClrsrchlik=" and concat(',',product_colour_ids,',') like ('%,$product_color_primary,%') ";
		} 
		 
		
		$sql="select * from srt_offer_list_master where parent_productline_id=:parent_productline_id and concat(',',productline_id,',') like ($prdlinesrchlik) and vechile_type=:vechile_type and date_format(offer_date, '%m-%Y')=date_format(curdate(), '%m-%Y') and registration_type=:registration_type $prdlineClrsrchlik order by offer_date desc limit 1";
		$bindArr=array(":parent_productline_id"=>array("value"=>$parent_product_line,"type"=>"int"), ":vechile_type"=>array("value"=>$vehicle_type,"type"=>"int"), ":registration_type"=>array("value"=>$registration_type,"type"=>"int")); 
		$recs = $this->pdoObj->fetchSingle($sql, $bindArr);  
	 
		//$cid=$this->purifyString($recs["booking_transaction_id"]);  
		
		if($ex_vechicle!="1")
		{
			$recs["exchange_offer_tata"]="0.00";
			$recs["exchange_offer_srt"]="0.00";
		}
		if($corporate_type!="1")
		{ 
			$recs["corporate_offer_tata"]="0.00"; 
			$recs["corporate_offer_srt"]="0.00"; 
		}
		
		$sendRs=$recs;
		
		$sendArr=array('rsData'=>$sendRs, 'modtype'=>$modtype, 'status'=>'success');  
		
		if($sendtype=='json') return json_encode($sendArr);
		else if($sendtype=='onlyrsarr') return $recs;
		else return $sendArr;
		 
	}
	public function getBookingReceiptDetailsView($postArr)
	{
		$getcid=$this->purifyInsertString($postArr["id"]);
		
		$sql="select rcp.*, case rcp.payment_mode when 1 then 'Cash' when 2 then 'Bank' when 3 then 'Vehicle exchange' when 4 then fnam.financier_name else '' end as paymode_desc from srt_receipts_transaction as rcp left join srt_finance_transaction as ft on (rcp.booking_transaction_id=ft.booking_transaction_id and rcp.payment_mode=4 and rcp.finance_transaction_id=ft.finance_transaction_id) left join srt_financier_master as fnam on ft.financier_id=fnam.financier_id where rcp.booking_transaction_id=:id and rcp.amount_reveived_status=1 and rcp.is_deleted<>1";
		$bindArr=array( ":id"=>array("value"=>$getcid,"dtype"=>"int"));
		$recs = $this->pdoObj->fetchMultiple($sql, $bindArr); 
		
		return $recs;
	}
	
	public function __destruct() 
	{
		
	} 
}

?>