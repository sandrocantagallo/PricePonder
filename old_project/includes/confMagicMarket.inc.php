<?php
$ay_link['MagicMarket'] = array 
(
	'url'		=>	'https://it.magiccardmarket.eu/?mainPage=showSearchResult&type[]=Artifact&type[]=Creature&type[]=Enchantment&type[]=Hero&type[]=Instant&type[]=Land&type[]=Plane&type[]=Planeswalker&type[]=Sorcery&type[]=Tribal&searchFor=',
	'sep-key'	=>	'+',
);
$ay_conf['MagicMarket'] = array
(
		0 =>	array	(
							'title_rule'	=>	'Ricerca Del Miglior Prezzo',
							'find'			=>	array ( 0 => 'td.outerRight', 1 => 'td.alignRight'),
							'extract'		=>	'innertext',
							'action'		=>	'save_best_price',
						),
);

?>