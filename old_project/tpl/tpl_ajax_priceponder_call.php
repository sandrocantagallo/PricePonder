<?php 
	switch ($action) {
		case 'show_card':
			 if ((isset($ay_card)) AND (is_array($ay_card))) { ?>
						<div class="col-sm-6 usercard">
							<div class="row">
						  		<div class="col-sm-5">
						         <a href="<?php echo base_url."card/".$ay_card['multiverseid'].".html"; ?>"> <img class="thumbnail img-responsive" title="<?=$ay_card['name']?>" src="<?=$cd_mtg_sets->get_card_img($ay_card['multiverseid'])?>"> </a>
						      </div>
						      <div class="col-sm-7">
					          	<ul class="list-group">
									<li class="list-group-item"><strong><a href="<?php echo base_url."card/".$ay_card['multiverseid'].".html"; ?>"><?=$ay_card['name']?></a></strong></li>
									<li class="list-group-item">
									<?php 
									
										//recupero tutti i set in cui è presente la carta 
										$ay_card_set = $cd_mtg_sets->get_card_set($ay_card['multiverseid']);
									
										//$ay_card_set = json_decode($ay_card['set']);
										echo PRICE_PONDER_SET_INFO.": <span class=\"badge badge-success\">".$ay_card_set[0]['name']."</span> <span class=\"badge badge-success\">".count($ay_card_set)."</span>";
									?>
									</li>
									<li class="list-group-item"><?=$ay_card['manaCost']?></li>
									<li class="list-group-item"><?=$ay_card['type']?></li>
									<?php if (!empty($ay_card['power'])) { ?>
									<li class="list-group-item"><?=$ay_card['power']?>/<?=$ay_card['toughness']?></li>
									<?php } ?>
									<li class="list-group-item"><?=$ay_card['text']?></li>
								</ul>
						      </div>
							</div>
						</div>
						<div  class="col-sm-6">
							<div id="price_list" class="list-group">
						
							</div>
			        	</div>
						<?php } else {
						
							?>
							<div class="col-sm-12 usercard">
								<div class="row">
									<div class="alert alert-danger">
			        					<strong>Nessun risultato!</strong> Mi spiace non ho trovato nessuna carta con questo nome sul portale <strong><?php echo $site; ?></strong>.
			      					</div>
			      				</div>
			      			</div>
							<?php
						
						}
			break;
		case 'show_price':
			?>
			
			<?php
			$i = 1;
			foreach ($ay_price as $key=>$price) {
				if (!empty($price[0]['save_best_price'])) {
					?>
								<a id="<?=$site?>" class="list-group-item <?php //if ($i == 1) echo "active"; ?>" href="http://adf.ly/1611586/<?=str_replace("http://", "", $price[0]['url'])?>" target="_blank">
				              		<button class="btn btn-sm <?php if ($i == 1) echo "btn-success"; else echo "btn-danger";?> pull-right" type="button"><?=CLICK_TO_BUY?></button>
				              		<h4 class="list-group-item-heading"><?=$key?></h4>
				              		<p class="list-group-item-text"><?=PRICE_FOUND?> <span class="price" id="<?=$site?>"><?=$price[0]['save_best_price']?></span> <?=PRICE_VALUTA?></p> 
				            	</a>
								<?php
								
								$i++;
								}
							}
							//se non ho trovato risultati
							if (($i == 1) AND (!empty($keys))){
								?>
								
								<a id="<?=$site?>" class="list-group-item alert-danger" href="#" target="_blank">
				              		<strong>Nessun risultato!</strong> Mi spiace non ho trovato nessuna carta con questo nome sul portale <strong><?php echo $site; ?></strong>.
				            	</a>
								
									<!--  <div class="alert alert-danger">
			        					<strong>Nessun risultato!</strong> Mi spiace non ho trovato nessuna carta con questo nome sul portale <strong><?php echo $site; ?></strong>.
			      					</div -->
								<?php
								
							}
				          
		break;
	}

?>
			
