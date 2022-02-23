<?php
$ay_link['Ebay'] = array 
(
	'url'		=>	'http://www.ebay.it/sch/Carte-Base-/38292/i.html?_sop=2&LH_BIN=1&_ex_kw=no+repack+proxy+leggere+bene+_+p9+lotto&_nkw=',
	'sep-key'	=>	'+',
);
$ay_conf['Ebay'] = array
(
		0 =>	array	(
							'title_rule'	=>	'Ricerca Del Miglior Prezzo',
							'find'			=>	'span.g-b',
							'extract'		=>	'innertext',
							'action'		=>	'save_best_price_ebay',
						),
);

?>