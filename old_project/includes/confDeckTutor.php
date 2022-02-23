<?php
$ay_link['DeckTutor'] = array 
(
	'url'		=>	'http://mtg.decktutor.com/search/version/category=cards/?q=',
	'sep-key'	=>	'+',
);
$ay_conf['DeckTutor'] = array
(
		0 =>	array	(
							'title_rule'	=>	'Ricerca Del Miglior Prezzo',
							'find'			=>	'td.lowest',
							'extract'		=>	'innertext',
							'action'		=>	'save_best_price',
						),
		1 =>	array	(
							'title_rule'	=>	'Ricerca Del Miglior Prezzo',
							'find'			=>	'td.price',
							'extract'		=>	'innertext',
							'action'		=>	'save_best_price',
		),
);

?>