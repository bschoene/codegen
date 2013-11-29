<?php

// print_r($_GET);

$get = $_GET;

$sections = array();
$matches = array();
$info    = array();

//Sort 
foreach($get as $key=>$value)
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

// print_r($sections);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<form>\n";
foreach($sections as $section)
{
		echo "\t<section>\n";
	
	foreach($section as $field)
	{
		
		foreach($field as $tag=>$information)
		{
			if($tag == "label")
			{
				echo "\t\t<$information>\n";
				$label = $information;
			}
			else
			{			
				echo "\t\t\t<$tag>$information</$tag>\n";
			}
			
		}
		echo "\t\t</$label>\n";
	}
	echo "\t</section>\n";
}
	echo "</form>";



?>

