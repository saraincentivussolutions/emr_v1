<?php
	include  dirname(realpath('..')).'/common/class.common.php'; 
	include 'class.script.php'; 
	$user_role = new user_role();
	$postArr = $_POST;
	$modules = $user_role->getMenuModuleList();
	$id=$user_role->purifyInsertString($postArr["id"]); 
	$module_chk_actions = array();
	if($id>0)
	{
		$rs=$user_role->getSingleView($postArr);
		$rsData = $rs['rsData'];
		$extra_json_elements = (array)$rsData['extra_json_elements'];
		//print_r($rsData);
		$user_mod_actions = $rsData['user_mod_actions'];
		
		foreach($user_mod_actions as $action)
		{
			$module_id = $action['module_id'];
			$mod_action = $action['module_actions'];
			$module_type = $action['module_type'];
			if($module_type==2)
			$module_chk_actions[$module_id] = $mod_action;
			else
			$parent_module_chk_actions[$module_id] = $mod_action;
			
		}
		
		//print_r($module_actions);
		
	}
?>	

<form role="form" id="frmUserRoleMaster">
 <input type="hidden" name="hid_id" id="hid_id" value="<?=$id;?>" />
<div class="content-wcommon">
         <section class="content-header">
          <h1>
            User Role
			 
		</h1>	
        </section>
        <section class="content">
          <div class="box-body white-bg">
  <div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control" id="user_role_name" name="user_role_name" placeholder="Enter Name" maxlength="50" value="<?=$rsData['user_role_name'];?>">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Status</label>
    <select class="form-control" id="user_role_status" name="user_role_status">
      <option value="1" <? if($rsData['user_role_status']==1) echo 'selected'; ?>>Active</option>
      <option value="2" <? if($rsData['user_role_status']==2) echo 'selected'; ?>>Inactive</option>
    </select>
  </div>
  
  <table class="form-table table table-bordered table-striped" id="customFields">
<thead>
<th>Module</th>
<th>Sub Module</th>
<th>Access</th>
<th>Add</th>
<th>Edit</th>
<th>Delete</th>
</thead>
 <tbody>
 <? 
 $parent = '';
 foreach($modules as $key=>$mod) { 
 
 
 
 ?>
 <tr>
<td>
<?  
	if($parent!=$mod['main_module_name']) {
	
	echo $mod['main_module_name'];
	
	if($mod['sub_module_id']>0)
	{
		$checked = ($parent_module_chk_actions[$mod['main_module_id']] == 1)?'checked':'';
		echo '</td><td></td><td>';
		echo '<input type="checkbox" name="hdn_module_parent_'.$mod['main_module_id'].'" class="chk_module_head" value="'.$mod['main_module_id'].'" '.$checked.'>';
		echo '</td>';
		echo '<td colspan=3></td>';
		echo '</tr>';
		echo '<tr><td>'; 
	}
	
	$parent = $mod['main_module_name'];
	 
	}
	
	$module_actions = explode(',', $mod['module_actions']);
	
	
	if($key == 0)
	{
	//$chkAction = explode(',', $parent_module_chk_actions[$mod['module_id']]);
	$checked = ($parent_module_chk_actions[$mod['main_module_id']] == 1)?'checked':'';
	?>
   </td>
<td><?=$mod['sub_module_name'];?></td>
<td><? if(in_array(1,$module_actions)) { ?><input type="checkbox" name="hdn_module_parent_<?=$mod['main_module_id'];?>[]" id="" value="1" class="chk_module_head" parent="<?=$mod['main_module_id'];?>" sub="<?=$mod['main_module_id'];?>" orgid="<?=$mod['main_module_id'];?>"  <?php echo $checked; ?>/><? } ?></td>
<td colspan=3></td>
 </tr>

    <?
	}
	else
	{
	$chkAction = explode(',', $module_chk_actions[$mod['module_id']]);
?>

</td>
<td><?=$mod['sub_module_name'];?></td>
<td><? if(in_array(1,$module_actions)) { ?><input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="" value="1" class="chk_module_sub sub_access" parent="<?=$mod['main_module_id'];?>" sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>"  <? if(in_array(1,$chkAction)) { echo "checked"; } ?>/><? } ?></td>
<td><? if(in_array(2,$module_actions)) { ?> <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="" value="2" class="chk_module_sub" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(2,$chkAction)) { echo "checked"; } ?>/> <? } ?></td>
<td><? if(in_array(3,$module_actions)) { ?> <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="" value="3" class="chk_module_sub" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(3,$chkAction)) { echo "checked"; } ?>/> <? } ?></td>
<td><? if(in_array(4,$module_actions)) { ?> <input type="checkbox" name="hdn_module_<?=$mod['module_id'];?>[]" id="" value="4" class="chk_module_sub" parent="<?=$mod['main_module_id'];?>"  sub="<?=$mod['sub_module_id'];?>" orgid="<?=$mod['module_id'];?>" <? if(in_array(4,$chkAction)) { echo "checked"; } ?>/> <? } ?></td>
 </tr>
<? 
}
} ?>
</table>
  </div>
  
  <div class="form-group">
				<div class="col-md-12 no-padding">			  		
					<label><input type="checkbox" class="extra_modules" name="approve_expense" value="1" <? if($extra_json_elements['approve_expense']==1) echo 'checked'; ?> /> Approve by accounts
                    </label>
				</div>	
                <div class="col-md-12 no-padding">			  		
					<label><input type="checkbox" class="extra_modules"  name="expense_to_others" value="1" <? if($extra_json_elements['expense_to_others']==1) echo 'checked'; ?> /> Approve by admin
                    </label>
				</div>	
				<div class="col-md-12 no-padding">			  		
					<label><input type="checkbox" class="extra_modules"  name="finance_by_customer" value="1" <? if($extra_json_elements['finance_by_customer']==1) echo 'checked'; ?> /> Finance list by customer
                    </label>
				</div>	
                <div class="col-md-12 no-padding">			  		
					<label><input type="checkbox" class="extra_modules"  name="finance_by_inhouse" value="1" <? if($extra_json_elements['finance_by_inhouse']==1) echo 'checked'; ?> /> Finance by in house
                    </label>
				</div>	
                <div class="col-md-12 no-padding">			  		
					<label><input type="checkbox" class="extra_modules"  name="finance_approve" value="1" <? if($extra_json_elements['finance_approve']==1) echo 'checked'; ?> /> Finance DO approve
                    </label>
				</div>	

	
				
			</div>
  
  <div class="col-md-4 no-padding pull-right">
		  	<p>&nbsp;</p>
			<div class="form-group">
				<div class="col-md-12 no-padding text-right">			  		
					<button class="btn btn-warning btn-sm pull-right" onclick="closeUserRoleMaster()" type="button"><i class="fa fa-times"></i> Cancel</button>
					<button class="btn btn-primary btn-sm pull-right" style="margin-right:10px;" onclick="CreateUpdateUserRoleMasterSave()" type="button"><i class="fa fa-floppy-o"></i> Submit</button>
				</div>	
			</div>
		  </div>
  
  </section>
  </div>
  
  
</form>