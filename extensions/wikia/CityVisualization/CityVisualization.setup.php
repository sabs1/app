<?php
/**
 * WikiaHomePage
 *
 * @author Andrzej 'nAndy' Łukaszewski
 * @author Hyun Lim
 * @author Marcin Maciejewski
 * @author Saipetch Kongkatong
 * @author Sebastian Marzjan
 * @author Damian Jóźwiak
 */

$dir = dirname(__FILE__) . '/';
$app = F::app();

$wgExtensionCredits['other'][] = array(
	'name'			=> 'CityVisualization',
	'author'		=> 'Andrzej "nAndy" Łukaszewski, Hyun Lim, Marcin Maciejewski, Saipetch Kongkatong, Sebastian Marzjan, Damian Jóźwiak',
	'description'	=> 'CityVisualization',
	'version'		=> 1.0
);

// helper hierarchy
// row assigners
$wgAutoloadClasses['WikiImageRowHelper'] = $dir.'classes/WikiImageRowHelper.class.php';

// getdata helpers
$wgAutoloadClasses['WikiGetDataHelper'] = $dir.'classes/WikiGetDataHelper.class.php';
$wgAutoloadClasses['WikiListConditioner'] = $dir.'classes/WikiListConditioner.class.php';
$wgAutoloadClasses['WikiListConditionerForVertical'] = $dir.'classes/WikiListConditionerForVertical.class.php';
$wgAutoloadClasses['WikiListConditionerForCollection'] = $dir.'classes/WikiListConditionerForCollection.class.php';
$wgAutoloadClasses['PromoImage'] = $dir.'/classes/PromoImage.class.php';

//classes
$classDir = $dir . '/classes';
$wgAutoloadClasses['PromoImage'] = $classDir.'/PromoImage.class.php';
$wgAutoloadClasses['BaseXWikiImage'] = $classDir.'/BaseXWikiImage.class.php';
$wgAutoloadClasses['PromoXWikiImage'] = $classDir.'/PromoXWikiImage.class.php';

$wgAutoloadClasses['WikiaHomePageHelper'] =  $dir.'/helpers/WikiaHomePageHelper.class.php';
$wgAutoloadClasses['CityVisualization'] =  $dir.'/models/CityVisualization.class.php';


