<?php
$ay_link['MagicMarket'] = array 
(
	'url'		=>	'https://it.magiccardmarket.eu/?mainPage=showSearchResult&type[]=Artifact&type[]=Creature&type[]=Enchantment&type[]=Hero&type[]=Instant&type[]=Land&type[]=Plane&type[]=Planeswalker&type[]=Sorcery&type[]=Tribal&searchFor=',
	'sep-key'	=>	'+',
);
$ay_conf['MagicMarket'] = array
(
		/*0 =>	array	(
							'title_rule'	=>	'Ricerca Del Miglior Prezzo',
							'find'			=>	array ( 0 => 'td.outerRight', 1 => 'td.alignRight'),
							'extract'		=>	'innertext',
							'action'		=>	'save_best_price',
						),*/
		0	=>	array		(
								'title_rule'	=>	'Ricerco linee Prodotto',
								'find'			=>	array(	0 => 'tr.row_Even', 1 => 'row_Odd'),
								'extract'		=>	'innertext',
								'rules'		=>	array  (
										
											0 => array ( 
													'title_rule' => 'Controllo corrispondenza sul titolo',
													'find'		=>	'td.col_3',
													'extract'	=>	'innertext',
													'action'	=>	'check_card_name',
													),
											1	=>	array (
													
													'title_rule'	=>	'Ricerca Del Miglior Prezzo',
													'find'			=>	array ( 0 => 'td.outerRight', 1 => 'td.alignRight'),
													'extract'		=>	'innertext',
													'action'		=>	'save_best_price',
													
													),
											
										
										
													),
				
							),
);

?>