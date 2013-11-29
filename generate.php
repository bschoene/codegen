<?php

$raw_data = $_GET;

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

$output=htmlspecialchars($output);
$output = str_replace("\n","<br />",$output);
$output = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$output);
$output = "<code>$output</code>";
echo $output;

?>