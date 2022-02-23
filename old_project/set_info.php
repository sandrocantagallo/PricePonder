<?php

try {

	$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : false;

	if (status_project != 'sviluppo') {
		//rendo invisibili tutti gli echo che lo script lancia durante l'esecuzione
		//ob_start();
	}
	//includo il file di configurazione
	require 'includes/config.inc.php';
	//istanzio la classe generica Couponit
	$ccp = new Couponit();
	//istanzio la clase di comunicazione con il db delle carte mtg importate
	$cd_mtg_sets = new Model_mtg_sets();

	$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);

	//recupero le informazioni sul set avente ID ricevuto via query
	$ay_set = $cd_mtg_sets->get_data(table_sets, 'id', 'DESC', '1', '0', ' WHERE id = \''.$id.'\'');
	
	/**
	 * SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = 44 AND `manaCost` like '%{W}%' AND `manaCost` not like '%{R}%' AND `manaCost` not like '%{U}%' AND `manaCost` not like '%{G}%' AND `manaCost` not like '%{B}%'
	 */
	
	$tot_w_cards = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'W');
	$tot_r_cards = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'R');
	$tot_g_cards = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'G');
	$tot_b_cards = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'B');
	$tot_u_cards = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'U');
	$tot_multicolored = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'multicolor');
	$tot_incolored = $cd_mtg_sets->get_all_cards_with_color_from_set($id, 'incolor');
	
	$tot_cards_set = $tot_w_cards+$tot_r_cards+$tot_g_cards+$tot_b_cards+$tot_u_cards+$tot_multicolored+$tot_incolored;
	
	$r_perc = round($tot_r_cards/($tot_cards_set/100));
	$w_perc = round($tot_w_cards/($tot_cards_set/100));
	$b_perc = round($tot_b_cards/($tot_cards_set/100));
	$u_perc = round($tot_u_cards/($tot_cards_set/100));
	$g_perc = round($tot_g_cards/($tot_cards_set/100));
	$m_perc = round($tot_multicolored/($tot_cards_set/100));
	$i_perc = round($tot_incolored/($tot_cards_set/100));
	
	//recupero la curva di mana del set
	$ay_curva_mana = $cd_mtg_sets->get_set_mana_cost_asset($id);
	
	//recupero il numero totale di sets che il sistema conosce
	$tot_sets = $cd_mtg_sets->tot_data(table_sets, 'id');
	//recupero il numero totale di carte
	$tot_cards = $cd_mtg_sets->tot_data(table_cards, 'multiverseid');
	//recupero l'ultimo set importato nel db
	$ay_last_set = $cd_mtg_sets->get_data(table_sets, 'releaseDate', 'DESC', '1', '0', '');
	$last_set = $ay_last_set[0]['name'];
	//istanzio le librerie curl
	$ch = curl_init();
	$ay_link = array();
	$ay_conf = array();
	

	if (status_project != 'sviluppo') {
		//mostro in output tutto quello che viene ora
		//ob_end_clean();
	}

	//includo il tpl del portale
	require_once 'tpl/tpl_set_info.php';

} catch (Exception $e) {
	echo "- Method Lunched: generate_log  - ".shell_hv;
	echo "------------------------------------------- ".shell_hv;
	echo $e->getMessage()." ".shell_hv;
	echo $e->getFile()." ".shell_hv;
	echo $e->getLine()." ".shell_hv;

}



?>
