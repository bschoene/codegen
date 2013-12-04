<?php

/**
 * Code Generator allows to create XML models for registration forms as
 * part of TMS (Ticket Management System)
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Generating
 * @package    TMS Registration
 * @author     Benjamin Schoene <benjamin.schoene@gmx.de>
 * @copyright  2013 Benjamin Schoene
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: generate.php,v 1 2013/13/03 18:40:36 bschoene Exp $
 * @link       http://www.cmsrevolution.de/codegen
 */

$raw_data = $_POST;														//Save $_POST information in $raw_data
/* information gets saved as array
 * - keys are associative
 * - keys follow pattern: [sectionno]informationtype[fieldno]
 *   Example: '1label1'=>'value'
 **/

$sections	= array();													//save / sort raw_data
$matches	= array();													//save preg_match_all > line 35
$info   	= array();													//save preg_match 	  > line 36	

/* Sort raw_data into $sections */
foreach($raw_data as $key=>$value)
{
	if(!empty($value))													//Value of variable not empty
	{
		preg_match_all('/\d+/', $key, $matches, PREG_PATTERN_ORDER);	//Get all numbers of $key
		preg_match('/\D+/', $key, $info);								//Get letters of $key
		$section_num	= $matches[0][0];								//get sectionno
		$field_num	= $matches[0][1];									//get fieldno
		$info		= $info[0];											//get info
		$sections[$section_num][$field_num][$info] = $value;			//sort information into 3d-array
	}
}

/* Instead of echo all strings getting added to $output
 * > allows to parse output
 **/
$output = "";
$output .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$output .= "<form>\n";
foreach($sections as $section)
{
	$output .= "\t<section>\n";
	foreach($section as $field)
	{
		foreach($field as $tag=>$information)
		{
			if($tag == "label")
			{
				$output .= "\t\t<$information>\n";
				$label = $information;
			}
			elseif($tag == 'options')									//Filter 'Optionen'
			{
				$options = explode(";", $information);					//Splitting options on ';'
				foreach($options as $option)
				{
					$output .= "\t\t\t<option>$option</option>\n";		//Add every option in single tag
				}
			}
			else
			{			
				$output .= "\t\t\t<$tag>$information</$tag>\n";
			}
			
		}
		$output .= "\t\t</$label>\n";
	}
	$output .= "\t</section>\n";
}
$output .= "</form>";


if(isset($_GET['download']))
{
	header('Content-type: application/force-download');					//Force download dialog
	header('Content-Disposition: attachment; filename="model.xml"');	//Rename to model.xml
	echo $output;
}
else
{
	//get $output into shape for HTML
	$output = htmlspecialchars($output);
	$output = str_replace("\n","<br />",$output);
	$output = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$output);
	$output = "<code>$output</code>";
	echo $output;
}

?>