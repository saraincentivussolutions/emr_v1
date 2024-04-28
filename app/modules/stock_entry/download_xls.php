<?php 
	$maindir = dirname(realpath('..'));
	include  $maindir.'/common/class.common.php'; 
	include 'class.script.php'; 
	ini_set('display_error',1);
	$stock_entry = new stock_entry();
	
	$postArr=$_POST;
	$action=$_POST["action"];
	
	if($action!='view') exit('illegal access!');
	
	$stockdata = $stock_entry->getStockMasterEntry();
	
	$parent_productlinelist = $stockdata['parent_productlinelist'];
	$productlinelist = $stockdata['productlinelist'];
	$productcolourlist = $stockdata['productcolourlist'];
	
	require_once($maindir.'/common/excel/Classes/PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	
	$file = dirname($maindir).'/stock_sheet.xls';
	
	if(file_exists($file)) unlink($file);
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	
	$title = 'Stock Entry';
	$totalColumns = 6;
	
	$r = 1;
	$arrHead = array('Date','Parent productline','Productline','Product colour','Chasis No.','Purchase cost','Status');
	
	foreach($arrHead as $key=>$name)
	{
		$objWorksheet->setCellValueByColumnAndRow($key,$r,$name);
	}
	
	$r++;
	
	/*$objPHPExcel->setActiveSheetIndex(1)
				->SetCellValue("A1", "UK")
				->SetCellValue("A2", "USA")
				->SetCellValue("A3", "CANADA")
				->SetCellValue("A4", "INDIA")
				->SetCellValue("A5", "POLAND")
				->SetCellValue("A6", "ENGLAND");*/
			
			$items = array (
'one, two, three',
'four, five, six');



$objWorkSheet = $objPHPExcel->createSheet(1); 
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue('A0', 'Parent productline');
foreach($parent_productlinelist as $key=>$list)
{
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+1), $list['parent_productline_name']);
}
$objPHPExcel->getActiveSheet()->setCellValue('B0', 'Productline');
foreach($productlinelist as $key=>$list)
{
	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+1), $list['productline_name']);
}

$objPHPExcel->getActiveSheet()->setCellValue('C0', 'Product colour');
foreach($productcolourlist as $key=>$list)
{
	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+1), $list['productcolour_name']);
}

$objPHPExcel->getActiveSheet()->setCellValue('D0', 'Status');
$stock_type = array('Open stock', 'G stock');
foreach($stock_type as $key=>$name)
{
	
	$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+1), $name);
}

$highestRow = $objPHPExcel->setActiveSheetIndex(1)->getHighestRow();
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('DropdownSheet');

$objPHPExcel->setActiveSheetIndex(0);

//$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();


for($r = 2; $r<10; $r++)
{

$objValidation = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$r)->getDataValidation();
$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
$objValidation->setAllowBlank(false);
$objValidation->setShowInputMessage(true);
$objValidation->setShowErrorMessage(true);
$objValidation->setShowDropDown(true);
$objValidation->setErrorTitle('Input error');
$objValidation->setError('Value is not in list.');
$objValidation->setPromptTitle('Pick from list');
$objValidation->setPrompt('Please pick a value from the drop-down list.');
$objValidation->setFormula1('DropdownSheet!$A$1:$A'.$highestRow);

$objValidation = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$r)->getDataValidation();
$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
$objValidation->setAllowBlank(false);
$objValidation->setShowInputMessage(true);
$objValidation->setShowErrorMessage(true);
$objValidation->setShowDropDown(true);
$objValidation->setErrorTitle('Input error');
$objValidation->setError('Value is not in list.');
$objValidation->setPromptTitle('Pick from list');
$objValidation->setPrompt('Please pick a value from the drop-down list.');
$objValidation->setFormula1('DropdownSheet!$B$1:$B'.$highestRow);


$objValidation = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$r)->getDataValidation();
$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
$objValidation->setAllowBlank(false);
$objValidation->setShowInputMessage(true);
$objValidation->setShowErrorMessage(true);
$objValidation->setShowDropDown(true);
$objValidation->setErrorTitle('Input error');
$objValidation->setError('Value is not in list.');
$objValidation->setPromptTitle('Pick from list');
$objValidation->setPrompt('Please pick a value from the drop-down list.');
$objValidation->setFormula1('DropdownSheet!$C$1:$C'.$highestRow);


$objValidation = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$r)->getDataValidation();
$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
$objValidation->setAllowBlank(false);
$objValidation->setShowInputMessage(true);
$objValidation->setShowErrorMessage(true);
$objValidation->setShowDropDown(true);
$objValidation->setErrorTitle('Input error');
$objValidation->setError('Value is not in list.');
$objValidation->setPromptTitle('Pick from list');
$objValidation->setPrompt('Please pick a value from the drop-down list.');
$objValidation->setFormula1('DropdownSheet!$D$1:$D'.$highestRow);





}



	/*$objValidation = $objPHPExcel->getActiveSheet()->getCell("C2")->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
		// ...
		// aaand the Magic code :)
	$objValidation->setFormula1('"'.implode('","', $items).'"');	*/
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($file);		
	$result = array('status'=>'success','file'=>'stock_sheet.xls');
	echo json_encode($result);
	
?>	