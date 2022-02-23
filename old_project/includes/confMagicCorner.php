<?php
$ay_link['MagicCorner'] = array
(
		'url'		=>	'www.magiccorner.it/Carte%20Magic/it-90-2114.aspx?s=',
		'sep-key'	=>	'+',
);
$ay_conf['MagicCorner'] = array
(
		0 =>	array	(
				'title_rule'	=>	'Ricerca della colonna contenente il prezzo',
				'find'			=>	'td.dat',
				'extract'		=>	'innertext',
				'action'		=>	'save_best_price_magiccorner',
		),
);

?>