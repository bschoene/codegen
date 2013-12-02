<?php

$raw_data = $_POST;

$sections = array();
$matches = array();
$info    = array();

//Sort 
foreach($raw_data as $key=>$value)
{
	if(!empty($value))
	{
		preg_match_all('/\d+/', $key, $matches, PREG_PATTERN_ORDER);
		preg_match('/\D+/', $key, $info);
		$sectionno = $matches[0][0];
		$fieldno = $matches[0][1];
		$info = $info[0];
		$sections[$sectionno][$fieldno][$info] = $value;
	}
}

$output="";
$output.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$output.="<form>\n";
foreach($sections as $section)
{
		$output.="\t<section>\n";
	
	foreach($section as $field)
	{
		
		foreach($field as $tag=>$information)
		{
			if($tag == "label")
			{
				$output.="\t\t<$information>\n";
				$label = $information;
			}
			elseif($tag == 'options')
			{
				$options = explode(";", $information);
				foreach($options as $option)
				{
					$output.="\t\t\t<option>$option</option>\n";
				}
			}
			else
			{			
				$output.="\t\t\t<$tag>$information</$tag>\n";
			}
			
		}
		$output.="\t\t</$label>\n";
	}
	$output.="\t</section>\n";
}
$output.="</form>";


if(isset($_GET['download']))
{
	// Force download
	header('Content-type: application/force-download');
	// Rename to model.xml
	header('Content-Disposition: attachment; filename="model.xml"');
	echo $output;
}
else
{
	$output=htmlspecialchars($output);
	$output = str_replace("\n","<br />",$output);
	$output = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$output);
	$output = "<code>$output</code>";
	echo $output;
}

?>