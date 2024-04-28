<?php
	$maindir = dirname(realpath('..'));
	include  $maindir.'/common/class.common.php'; 
	include 'class.script.php'; 
	
	$bookingreport = new bookingreport(); 
	
	$postArr=$_POST;
	$action=$_POST["action"];
	
	if($action!='view') exit('illegal access!');
	
	$get_arr = $bookingreport->listview($postArr); 
	$rsData = $get_arr['rsData']; 
	
	
	
	 
	
	$dispArr = array();
	$dispTabArr = array();
	
	foreach($rsData as $rsVal)
	{
		
		if(!in_array($rsVal["sales_team_name"],$dispTabArr)) $dispTabArr[]=$rsVal["sales_team_name"];
		
		$dispArr[$rsVal["sales_team_name"]][$rsVal["customer_advisor_name"]][]=$rsVal;		
	}
	

	include $maindir.'/common/fpdf/PrintPosition.php';

	class RPDF extends PDF
	{
		function Header()
		{
		}
		
		function Footer()
		{
			// Go to 1.5 cm from bottom
			$this->SetY(-8);
			// Select Arial italic 8
			$this->SetFont('Verdana','',8);
			
			$this->SetFillColor(100, 100, 100);
			
			$this->SetX(115);
			$this->Cell(0,8,'Page '.$this->PageNo().' of '.$this->AliasNbPages,0,0,'L');
			$this->SetX(183);
			$this->Cell(0,8,'Copyright © '.date('Y'),0,0,'L');
		
			$this->ln(4);
			
			$this->AliasNbPages();
			
			//$this->Cell(0,10,'Page '.$this->PageNo().' of '.$this->AliasNbPages,0,0,'C');
		}
		
		function CheckPageBreak($h)
		{
			$h = 15;
			//$this->PageBreakTrigger = 230;
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>170)
			{
				$this->AddPage($this->CurOrientation);
			}
		}
		
		function printTableHeader()
		{
			/*$tableWidth = $this->tableWidth;
			$tableColumns = $this->tableColumns;
			$tableAlign = $this->tableAlign;
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],8,$column,1,0,$tableAlign[$colkey]);
			}
			
			$this->ln(8);*/
			
			$tableColumns = $this->tableColumns;
			$this->RowHead($tableColumns);
		}
		
		function printTableRow()
		{
			/*$tableWidth = $this->tableWidth;
			
			$tableAlign = $this->tableAlign;
			
			foreach($this->tableColumns as $colkey=>$column)
			{
				$this->Cell($tableWidth[$colkey],8,$row[$colkey],1,0,$tableAlign[$colkey]);
			}	
			
			$this->ln(8);*/
			
		}
		
		public function showCustomerAdvisor()
		{
			$this->Cell(0,8,$this->customer_advisor,1,0,'L');
			$this->ln(8);
		}
		
		public function showSalesTeam()
		{
			$this->SetFont('Verdana','',12);
			$this->Cell(0,8,$this->sales_head_name,1,0,'C');
			$this->ln(8);
			$this->SetFont('Verdana','',9);
		}
		
		function RowHead($data)
		{
			$this->SetTextColor(204,229,255);
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++)
			{
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h, 'F');
				//Print the text
				$this->MultiCell($w,5,$data[$i],1,$a,0);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
			$this->SetTextColor(0,0,0);
		}
		
		function Row($data)
		{
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++)
			{
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				$this->MultiCell($w,5,$data[$i],0,$a);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		
		function NbLines($w,$txt)
		{
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb)
			{
				$c=$s[$i];
				if($c=="\n")
				{
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax)
				{
					if($sep==-1)
					{
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			return $nl;
		}
	}
	
	$title = "SRT Booking Report";

	$pdf = new RPDF('L');
	$pdf->AliasNbPages();
	$pdf->AddFont('Verdana-Bold','','verdana_bold.php');
	$pdf->AddFont('Verdana','','verdana.php');
	$pdf->AddPage();
	$pdf->SetLeftMargin(10);
	$pdf->SetRightMargin(10);
	
	$pdf->SetXY(15,100);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Verdana','',14);
	$pdf->Cell(0,15,$title,1,0,'C');
	$pdf->ln(5);
	$pdf->SetFillColor(51,51,255);
	$pdf->SetDrawColor(51,51,255);
	
	if(count($dispTabArr)>0)	
	{
	
		
		$tableColumns = array('S.No','Ord. Date','Ord. Status','Cust. Name','Cust. Mobile','Product Line','Veh. Quote','Contribution Offer','SRT additional','Received payment','Finance by','Financier','Loan amount','Finance status');
		$tableWidth = array(10, 15, 15, 25, 25, 20, 25, 22, 20, 20, 20, 20, 20, 20);
		$tableAlign = array('C','L','L','L', 'L', 'L','L', 'R', 'R', 'R', 'R', 'L','R', 'L');
		
		$pdf->tableColumns = $tableColumns;
		$pdf->widths = $tableWidth;
		$pdf->aligns = $tableAlign;
		$pdf->SetFont('Verdana','',9);
		
		
		
		foreach($dispTabArr as $dispTabNames)
		{
			$pdf->AddPage(); // expense sep page comment
		
			//$pdf->SetX(13);
			
			$pdf->sales_head_name = 'Sales team: '.$dispTabNames;
			
			$pdf->showSalesTeam();
			$pdf->printTableHeader();
			
			$caArr=$dispArr[$dispTabNames]; 
					 
			foreach($caArr as $custAdvisorName=>$custAdvisorVals)
			{
				$pdf->customer_advisor = 'CA: '.$custAdvisorName;
				$pdf->showCustomerAdvisor(); 
				
				$caSno=0;
				foreach($custAdvisorVals as $dispCustBkDet)
				{
					$caSno++;
					$rowArr = array($caSno, $bookingreport->convertDate($bookingreport->purifyString($dispCustBkDet["order_date"])), $dispCustBkDet["orderstatus_name"], $dispCustBkDet["customer_name"], $dispCustBkDet["customer_mobile"], $dispCustBkDet["productline_name"], number_format($dispCustBkDet["onroad_price"],2), number_format($dispCustBkDet["contribution_offer"],2), number_format($dispCustBkDet["srt_addition_offer"],2), number_format($dispCustBkDet["bk_amount_received"],2), $dispCustBkDet["finance_desc"], $dispCustBkDet["financier_name"], $dispCustBkDet["finance_amount"],$dispCustBkDet["fin_status"]);
					$pdf->row($rowArr);
					
				}
				
			}
		} 
		
		/*foreach($expDisp as $expLpKey=>$expLpVal)
		{
			$pdf->subHeaderName = 'Employee name : '.$expLpKey;
			//$pdf->AddPage(); // expense sep page uncomment
			$pdf->ln(8);		
			$pdf->Cell(182,8,'Employee name : '.$expLpKey,1,0,'C');
			$pdf->ln(8);
			
			$pdf->printTableHeader();
			//$pdf->ln(8);
			
			foreach($expLpVal["data"] as $expDataVal)
			{
				$rowArr = array($expDataVal["exp_date"],  $expDataVal["cat_name"], $expDataVal["subcat_name"], number_format($expDataVal["exp_amount"],2));
				$pdf->printTableRow($rowArr);
			}
			//$pdf->ln(8);
			$pdf->Cell(142,8,'Total',1,0,'C');
			$pdf->Cell(40,8, number_format($expLpVal["total"],2),1,0,'R');
			$pdf->ln(8);
			
		}*/
	}
	$filePath =dirname($maindir).'/booking_report.pdf';
	$pdf->Output($filePath, 'F');
	$filePath = 'booking_report.pdf';
	$result = array('status'=>'success','file'=>$filePath.'?'.uniqid());
	echo json_encode($result);
?>