<?php
$ay_link['Lurkoneshop'] = array 
(
	'url'		=>	'http://www.lurkoneshop.it/index.php?Search=Cerca&Itemid=0&option=com_virtuemart&page=shop.browse&vmcchk=1&Itemid=1&keyword=',
	'sep-key'	=>	'+',
);
$ay_conf['Lurkoneshop'] = array
(
		0 =>	array	(
							'title_rule'	=>	'Recupero del DIV principale del portale',
							'find'			=>	'div#vmMainPage',
							'extract'		=>	'innertext',
							'rules'			=>	array(
														0	=>	array(
																	'title_rule'	=>	'Ricerca Del Miglior Prezzo',
																	'find'			=>	'span.productPrice',
																	'extract'		=>	'innertext',
																	'action'		=>	'save_best_price_lurkone',
																),
									),
						),
);

?>