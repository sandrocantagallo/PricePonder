<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebApplication"> 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (isset($ay_card_info)) { ?>
    
    	<title>Mtg Card Detail: <?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?></title>
		<meta name="description" content="<?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?> " />
   
   		<!-- Google Authorship and Publisher Markup -->
		<link rel="author" href="https://plus.google.com/u/0/104665814574504531350/posts"/>
		<link rel="publisher" href="https://plus.google.com/u/0/104665814574504531350/"/>
		
		<!-- Schema.org markup for Google+ -->
		<meta itemprop="name" content="PricePonder - <?php echo $ay_card_info[0]['name']; ?>">
		<meta itemprop="description" content="<?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?>">
		<meta itemprop="image" content="<?=$cd_mtg_sets->get_card_img($ay_card_info[0]['multiverseid'])?>">
		
		<!-- Twitter Card data -->
		<meta name="twitter:card" content="Price Ponder - <?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?>">
		<meta name="twitter:site" content="@<?php echo $ay_card_info[0]['name']; ?>">
		<meta name="twitter:title" content="<?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?>">
		<meta name="twitter:description" content="<?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?>">
		<meta name="twitter:creator" content="@<?php echo $ay_card_info[0]['name']; ?>">
		<!-- Twitter summary card with large image must be at least 280x150px -->
		<meta name="twitter:image:src" content="<?=$cd_mtg_sets->get_card_img($ay_card_info[0]['multiverseid'])?>">
		
		<!-- Open Graph data -->
		<meta property="og:title" content="Price Ponder <?php echo $ay_card_info[0]['name']; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php echo base_url."card/".$ay_card_info[0]['multiverseid'].".html"; ?>" />
		<meta property="og:image" content="<?=$cd_mtg_sets->get_card_img($ay_card_info[0]['multiverseid'])?>" />
		<meta property="og:description" content="Price Ponder - <?php echo $ay_card_info[0]['name']." - ".$ay_set_name[0]['name']." - ".$ay_card_info[0]['type']." - ".$ay_card_info[0]['originalText']; ?>" />
		<meta property="og:site_name" content="PricePonder" />
		<meta property="article:section" content="Magic The Gathering" />
		<meta property="article:tag" content="Magic The Gathering" />
		<meta property="fb:admins" content="1521053199" /> 
    
    <?php } else { ?>
    	
    	<title>Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani</title>
		<meta name="description" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani" />
   
   		<!-- Google Authorship and Publisher Markup -->
		<link rel="author" href="https://plus.google.com/u/0/104665814574504531350/posts"/>
		<link rel="publisher" href="https://plus.google.com/u/0/104665814574504531350/"/>
		
		<!-- Schema.org markup for Google+ -->
		<meta itemprop="name" content="PricePonder">
		<meta itemprop="description" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani">
		<meta itemprop="image" content="http://localhost/priceponder/images/priceponder.jpg">
		
		<!-- Twitter Card data -->
		<meta name="twitter:card" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani">
		<meta name="twitter:site" content="@Risparmiamo">
		<meta name="twitter:title" content="PricePonder - Il portale dove cercare il miglior prezzo di acquisto per giochi di carte collezzionabili">
		<meta name="twitter:description" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani">
		<meta name="twitter:creator" content="@Risparmiamo">
		<!-- Twitter summary card with large image must be at least 280x150px -->
		<meta name="twitter:image:src" content="http://localhost/priceponder/images/priceponder.jpg">
		
		<!-- Open Graph data -->
		<meta property="og:title" content="Price Ponder" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="http://localhost/priceponder/" />
		<meta property="og:image" content="http://localhost/priceponder/images/priceponder.jpg" />
		<meta property="og:description" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani" />
		<meta property="og:site_name" content="PricePonder" />
		<meta property="article:section" content="Magic The Gathering" />
		<meta property="article:tag" content="Magic The Gathering" />
		<meta property="fb:admins" content="1521053199" /> 
   
    <?php } ?>
    
	
    <!-- Bootstrap -->
    <link href="<?php echo base_url; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-table.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/custom.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/demo.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/fileinput.css" rel="stylesheet">
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <!-- FACEBOOK LIKE COUNTER -->
  <div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Processing... Load Card</h4>
            </div>
            <div class="modal-body">
	   			<div class="progress">
					<div id="progress-bar" class="progress-bar progress-bar-info active" style="width: 5%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar">
					<span class="sr-only">5% Complete</span>
					</div>
				</div>
            </div>
           
        </div>
    </div>
</div>
<div id="fb-root"></div>

  <!-- END FACEBOOK LIKE COUNTER -->  
  <div class="container theme-showcase" role="main">
  
  	<?php require_once 'tpl/tpl_menu.php';?>
  
	  
	
	
	
	
	
	<div id="ajax-content" class="row">
	
	</div>
  	<!-- contenitore risultati -->	
  	<?php 
  		if ((isset($ay_price)) AND (is_array($ay_price))) {
			?>
			<div class="row">
			<?php if (isset($ay_card)) { ?>
			<div class="col-sm-6 usercard">
				<div class="row">
			  		<div class="col-sm-5">
			          <img class="thumbnail" title="<?=$ay_card['name']?>" src="<?=$ay_card['img']?>">
			      </div>
			      <div class="col-sm-7">
		          	<ul class="list-group">
						<li class="list-group-item"><strong><?=$ay_card['name']?></strong></li>
						<li class="list-group-item">
						<?php 
							$ay_card_set = json_decode($ay_card['set']);
							echo PRICE_PONDER_SET_INFO.": <span class=\"badge badge-success\">".$ay_card_set[0]."</span> <span class=\"badge badge-success\">".count($ay_card_set)."</span>";
						?>
						</li>
						<li class="list-group-item"><?=$ay_card['manacost']?></li>
						<li class="list-group-item"><?=$ay_card['type']?></li>
						<?php if (!empty($ay_card['pt'])) { ?>
						<li class="list-group-item"><?=$ay_card['pt']?></li>
						<?php } ?>
						<li class="list-group-item"><?=$ay_card['text']?></li>
					</ul>
			      </div>
				</div>
			</div>
			<?php } ?>
			<div class="<?php if (isset($ay_card)) { echo "col-sm-6"; } else { echo "col-sm-12"; }?>">
	          <div class="list-group">
	          <?php 
	          	$i = 1;
	          	foreach ($ay_price as $key=>$price) {
					if (!empty($price[0]['save_best_price'])) {
					?>
					<a class="list-group-item <?php if ($i == 1) echo "active"; ?>" href="http://adf.ly/1611586/<?=str_replace("http://", "", $price[0]['url'])?>" target="_blank">
	              		<button class="btn btn-sm <?php if ($i == 1) echo "btn-success"; else echo "btn-danger";?> pull-right" type="button"><?=CLICK_TO_BUY?></button>
	              		<h4 class="list-group-item-heading"><?=$key?></h4>
	              		<p class="list-group-item-text"><?=PRICE_FOUND?> <?=$price[0]['save_best_price']?> <?=PRICE_VALUTA?></p> 
	            	</a>
					<?php
					
					$i++;
					}
				}
				//se non ho trovato risultati
				if (($i == 1) AND (!empty($keys))){
					?>
						<div class="alert alert-danger">
        					<strong>Nessun risultato!</strong> Mi spiace non ho trovato nessuna carta con questo nome.
      					</div>
					<?php
					
				}
	          ?>
	          </div>
        	</div>
        	</div>
			<?php 
		}
  	?>
  	
  	<?php 
  	if (isset($ay_card_info)) {
	
		?>
		
		

   

		
		
		<div id="card-info">
		
		<ul class="breadcrumb">

        	<li><a href="<?php echo base_url; ?>">Home</a></li>

        	<li><a href="<?php echo base_url."set/".$ay_set_name[0]['id'].".html"?>"><?php echo $ay_set_name[0]['name']; ?></a></li>

        	<li class="active"><a href="<?php echo base_url."card/".$ay_card_info[0]['multiverseid'].".html"; ?>"><?=$ay_card_info[0]['name']?></a></li>

    	</ul>
		
		<div class="row">
		<div class="col-sm-12 usercard">
				
			  		<div class="col-sm-3">
			         <a href="<?php echo base_url."card/".$ay_card_info[0]['multiverseid'].".html"; ?>"> <img class="thumbnail" title="<?=$ay_card_info[0]['name']?>" src="<?=$cd_mtg_sets->get_card_img($ay_card_info[0]['multiverseid'])?>"> </a>
			      </div>
			      <div class="col-sm-9">
		          	<ul class="list-group">
						<li class="list-group-item"><strong><a href="<?php echo base_url."card/".$ay_card_info[0]['multiverseid'].".html"; ?>"><?=$ay_card_info[0]['name']?></a></strong> - <?php echo $ay_card_info[0]['rarity']; ?></li>
						<li class="list-group-item">
						<?php 
						
							if (is_array($ay_set_name)) {
								
								echo PRICE_PONDER_SET_INFO.": ";

								foreach ($ay_set_name as $set_name) {
								
									echo " <span class=\"badge badge-success\">".$set_name['name']."</span>";
								}
												
									echo "<span class=\"badge badge-success\">".count($ay_set_name)."</span>";
							}
							//$ay_card_set = json_decode($ay_card_info[0]['set']);
							//echo PRICE_PONDER_SET_INFO.": <span class=\"badge badge-success\">".$ay_card_set[0]."</span> <span class=\"badge badge-success\">".count($ay_card_set)."</span>";
						?>
						</li>
						<li class="list-group-item"><?=$ay_card_info[0]['manaCost']?> <?php echo PRICE_PONDER_CONVERTED_MANA_COST; ?> <?php echo $ay_card_info[0]['cmc']; ?></li>
						<li class="list-group-item"><?=$ay_card_info[0]['type']?></li>
						<?php if (!empty($ay_card_info[0]['power'])) { ?>
						<li class="list-group-item"><?=$ay_card_info[0]['power']?>/<?=$ay_card_info[0]['toughness']?></li>
						<?php } ?>
						<li class="list-group-item">
							<?=$ay_card_info[0]['originalText']?> 
							<br />
							<?=$ay_card_info[0]['flavor']?> 
							<br />
							<?=$ay_card_info[0]['artist']?> 
						</li>
						<li class="list-group-item">
						<?php 
						
							foreach ($ay_legal as $legal) {
							
							$not_found = true;

									foreach ($ay_card_legality as $card_legality) {
										
										if ($card_legality['where'] == $legal) {

											$not_found = false;

											switch ($card_legality['legal']) {
											
												case 'Banned':
													echo "&nbsp; <span class=\"label label-danger\">".$legal." ".$card_legality['legal']."</span> &nbsp;";
													break;
												case 'Restricted':
													echo "&nbsp; <span class=\"label label-warning\">".$legal." ".$card_legality['legal']."</span> &nbsp;";
													break;
												case 'Legal':
													echo "&nbsp; <span class=\"label label-success\">".$legal." ".$card_legality['legal']."</span> &nbsp;";
													break;

											}

										}
										
										

									}

									if ($not_found) {
										echo "&nbsp; <span class=\"label label-danger\">".$legal." ".PRICE_PONDER_OUT_FORMAT."</span> &nbsp;";
									}
								

							}
						
						?>
						</li>
					</ul>
			      </div>
				</div>
			</div>

			
		<div class="row">
			<div class="col-sm-12">
			<?php 
			$url_key = str_replace(" ", "+", $ay_card_info[0]['name']);
			$ay_site_price = array (
				'DeckTutor'		=>	'http://mtg.decktutor.com/search/version/category=cards/?q='.$url_key,
				'Ebay'			=>	'http://www.ebay.it/sch/Cards-/39243/i.html?_sop=2&LH_BIN=1&_ex_kw=no+repack+proxy+leggere+bene+_+p9+lotto&_nkw='.$url_key,
				'Lurkoneshop'	=>	'http://www.lurkoneshop.it/index.php?Search=Cerca&Itemid=0&option=com_virtuemart&page=shop.browse&vmcchk=1&Itemid=1&keyword='.$url_key,
				'MagicCorner'	=>	'www.magiccorner.it/Carte%20Magic/it-90-2114.aspx?s='.$url_key,
				'MagicMarket'	=>	'https://it.magiccardmarket.eu/?mainPage=showSearchResult&type[]=Artifact&type[]=Creature&type[]=Enchantment&type[]=Hero&type[]=Instant&type[]=Land&type[]=Plane&type[]=Planeswalker&type[]=Sorcery&type[]=Tribal&searchFor='.$url_key,		
			);
			if (is_array($ay_price_card)) {
				$i = 1;
				foreach ($ay_price_card as $price_card) {
					
					if ($price_card['price'] != '') {
						
						?>
						<a id="<?=$price_card['shop_code']?>" class="list-group-item <?php if ($i == 1) echo "active"; ?>" href="http://adf.ly/1611586/<?=str_replace("http://", "", $ay_site_price[$price_card['shop_code']])?>" target="_blank">
				              		<button class="btn btn-sm <?php if ($i == 1) echo "btn-success"; else echo "btn-danger";?> pull-right" type="button"><?=CLICK_TO_BUY?></button>
				              		<h4 class="list-group-item-heading"><?=$price_card['shop_code']?></h4>
				              		<p class="list-group-item-text"><?=PRICE_FOUND?> <span class="price" id="<?=$price_card['shop_code']?>"><?=$price_card['price']?></span> <?=PRICE_VALUTA?> <span class="badge badge-success"><?=$price_card['day']?></span></p> 
				            	</a>
						<?php
						$i++;
					}

				}

			} else {
			?>
			
				<button type="button" id="scopri-prezzo" class="btn btn-lg btn-success center-block">Scopri il prezzo</button>
				
			<?php 		
			}
			?>
			</div>
		</div>
		
		<hr />
		
		<div class="row">
		
		<div class="col-sm-8">
	          <div class="panel panel-default">
	            <div class="panel-heading">
	              <h3 class="panel-title"><?php echo PRICE_PONDER_CARD_RULES; ?></h3>
	            </div>
	            <div class="list-group">
	            <?php  if (is_array($ay_card_rules)) { ?>
	            <?php 
	            
	            	foreach ($ay_card_rules as $card_rule) {

						?>
						 <a class="list-group-item"><span class="badge badge-success"><?php echo $card_rule['date']; ?></span> <?php echo $card_rule['text']; ?></a>
						<?php

					}
	            
	            ?>
	             <?php } ?>     
	           
	            </div>
	          </div>
	        </div>  
	       
	        <div class="col-sm-4">
	          <div class="panel panel-default">
	            <div class="panel-heading">
	              <h3 class="panel-title"><?php echo PRICE_PONDER_CARD_NAMES; ?></h3>
	            </div>
	            <div class="list-group">
	            
	            <?php 
	            
	            	foreach ($ay_card_language as $card_name) {
						if (strpos($card_name['name'],'?') === false) {
						?>
						 <a class="list-group-item"><span class="badge badge-success"><?php echo $card_name['language']; ?></span> <?php echo $card_name['name']; ?></a>
						<?php
						}
					}
	            
	            ?>
	            
	           
	            </div>
	          </div>
	        </div>       
	    </div>
	    <?php 
					
		//recupero tutti i prezzi di questa carta
		$ay_price_multiverseid_magicmarket = $cd_mtg_sets->get_data('mtg_cards_price', 'day', 'desc', '50', '0', ' where shop_code = \'MagicMarket\' AND multiverseid = \''.$ay_card_info[0]['multiverseid'].'\' ');
		$ay_price_multiverseid_dectutor = $cd_mtg_sets->get_data('mtg_cards_price', 'day', 'desc', '50', '0', ' where shop_code = \'DeckTutor\' AND multiverseid = \''.$ay_card_info[0]['multiverseid'].'\' ');
		$ay_price_multiverseid_ebay = $cd_mtg_sets->get_data('mtg_cards_price', 'day', 'desc', '50', '0', ' where shop_code = \'Ebay\' AND multiverseid = \''.$ay_card_info[0]['multiverseid'].'\' ');
		$ay_price_multiverseid_lurkoneshop = $cd_mtg_sets->get_data('mtg_cards_price', 'day', 'desc', '50', '0', ' where shop_code = \'Lurkoneshop\' AND multiverseid = \''.$ay_card_info[0]['multiverseid'].'\' ');
		$ay_price_multiverseid_magiccorner = $cd_mtg_sets->get_data('mtg_cards_price', 'day', 'desc', '50', '0', ' where shop_code = \'MagicCorner\' AND multiverseid = \''.$ay_card_info[0]['multiverseid'].'\' ');
		if (is_array($ay_price_multiverseid_magicmarket) AND (count($ay_price_multiverseid_magicmarket)>1)) {
					
		?>
	    <!-- Genero il grafico con l'andamento del prezzo dell carta -->
	    <div class="row">
	    	<div class="col-sm-12 col-md-12">
	    		<canvas id="price-<?php echo $ay_card_info[0]['multiverseid']; ?>" style="height:450px; width:600px;"></canvas>
	    		<div id="placeholder-<?php echo $ay_card_info[0]['multiverseid']; ?>"></div>
	    		<script>

	    		var options = {
	    				  legendTemplate : '<ul>'
	    				                  +'<% for (var i=0; i<datasets.length; i++) { %>'
	    				                    +'<li>'
	    				                    +'<span style=\"background-color:<%=datasets[i].lineColor%>\"></span>'
	    				                    +'<% if (datasets[i].label) { %><%= datasets[i].label %><% } %>'
	    				                  +'</li>'
	    				                +'<% } %>'
	    				              +'</ul>'
	    				  }
				
		    		var lineChartData = {
		    				labels : [<?php 
		    				
		    					if (is_array($ay_price_multiverseid_magicmarket)) {
										foreach ($ay_price_multiverseid_magicmarket as $price_magic_market) {
											echo '"'.$price_magic_market['day'].'",';
										}
								}	
		    				
		    				?>],
		    				datasets : [
							<?php 
									
									if (is_array($ay_price_multiverseid_magicmarket)) {
											?>
											{
					    						label: "Magic Market",
					    						fillColor : "rgba(220,220,220,0.2)",
					    						strokeColor : "rgba(220,220,220,1)",
					    						pointColor : "rgba(220,220,220,1)",
					    						pointStrokeColor : "#fff",
					    						pointHighlightFill : "#fff",
					    						pointHighlightStroke : "rgba(220,220,220,1)",
					    						data : [
												<?php

											foreach ($ay_price_multiverseid_magicmarket as $price_magic_market) {
												echo '"'.$price_magic_market['price'].'",';
											}
											
											?>
											]
					    					},
									<?php
									}	
									?>
									<?php
									if (is_array($ay_price_multiverseid_dectutor)) {
										?>
										{
				    						label: "Deck Tutor",
				    						fillColor : "rgba(151,187,205,0.2)",
				    						strokeColor : "rgba(151,187,205,1)",
				    						pointColor : "rgba(151,187,205,1)",
				    						pointStrokeColor : "#fff",
				    						pointHighlightFill : "#fff",
				    						pointHighlightStroke : "rgba(220,220,220,1)",
				    						data : [
											<?php

										foreach ($ay_price_multiverseid_dectutor as $price_magic_market) {
											echo '"'.$price_magic_market['price'].'",';
										}
										
										?>
										]
				    					},
								<?php
								}	
								?>
								<?php
								if (is_array($ay_price_multiverseid_ebay)) {
									?>
									{
			    						label: "Ebay",
			    						fillColor : "rgba(150,253,164,0.2)",
			    						strokeColor : "rgba(150,253,164,1)",
			    						pointColor : "rgba(150,253,164,1)",
			    						pointStrokeColor : "#fff",
			    						pointHighlightFill : "#fff",
			    						pointHighlightStroke : "rgba(150,253,164,1)",
			    						data : [
										<?php

									foreach ($ay_price_multiverseid_ebay as $price_magic_market) {
										echo '"'.$price_magic_market['price'].'",';
									}
									
									?>
									]
			    					},
							<?php
							}	
							?>
							<?php
							if (is_array($ay_price_multiverseid_lurkoneshop)) {
								?>
								{
		    						label: "Lurkone Shop",
		    						fillColor : "rgba(251,102,30,0.2)",
		    						strokeColor : "rgba(251,102,30,1)",
		    						pointColor : "rgba(251,102,30,1)",
		    						pointStrokeColor : "#fff",
		    						pointHighlightFill : "#fff",
		    						pointHighlightStroke : "rgba(251,102,30,1)",
		    						data : [
									<?php

								foreach ($ay_price_multiverseid_lurkoneshop as $price_magic_market) {
									echo '"'.$price_magic_market['price'].'",';
								}
								
								?>
								]
		    					},
						<?php
						}	
						?>
						<?php
						if (is_array($ay_price_multiverseid_magiccorner)) {
							?>
							{
	    						label: "Magic Corner",
	    						fillColor : "rgba(248,153,180,0.2)",
	    						strokeColor : "rgba(248,153,180,1)",
	    						pointColor : "rgba(248,153,180,1)",
	    						pointStrokeColor : "#fff",
	    						pointHighlightFill : "#fff",
	    						pointHighlightStroke : "rgba(248,153,180,1)",
	    						data : [
								<?php

							foreach ($ay_price_multiverseid_magiccorner as $price_magic_market) {
								$ay_price = explode("-", $price_magic_market['price']);
								$int = $ay_price[0];
								echo '"'.$int.'",';
							}
							
							?>
							]
	    					},
					<?php
					}	
					?>
		    					
		    				]
	
		    			}
	
		    		window.onload = function(){
		    			var ctx = document.getElementById("price-<?php echo $ay_card_info[0]['multiverseid']; ?>").getContext("2d");
		    			window.myLine = new Chart(ctx).Line(lineChartData, {
		    				responsive: true,
		    				
		    			});
		    			legend(document.getElementById('placeholder-<?php echo $ay_card_info[0]['multiverseid']; ?>'), lineChartData);
		    		}

	    		</script>
	    		
	    	</div>
	    </div>
	    <?php 
	    	}
	    ?>
		</div>
		<?php
		

	}
  	?>
  	
  	<!-- contenitore barra di ricerca -->
  	<div class="row">
  		 <div class="col-sm-12 col-md-12">
	        <form class="navbar-form" role="search" method="post">
	        <div class="input-group">
	            <input type="text" class="typeahead form-control" name="keys" id="keys" data-provide="typeahead" autocomplete="off" placeholder="Search" name="q" value="<?=$keys?>">
	            <div class="input-group-btn">
	                <button id="search-form" class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
	            </div>
	        </div>
	        </form>
	    </div>
  	</div>
  	<!-- fine contenitore barra di ricerca -->
  	
  	<!-- contenitore risultati -->	
  	
	
	<div class="row">
	
	<div class="page-header">
			<h1><?=PRICE_PONDER_CARDS_HISTORY?></h1>
		</div>
	
	 <div class="col-sm-12">
        
        	<!-- tabella ultime carte recuperate -->
        <div id="events-result"></div>
        	<table id="table-pagination" data-url="<?php echo base_url; ?>last_cards.php" data-toggle="table" data-height="400" data-pagination="true" data-search="true">
			    <thead>
			        <tr>
			            <!--  <th data-field="state" data-checkbox="true"></th>  -->
			            <th data-field="name" data-align="right" data-sortable="true">Name</th>
			            <th data-field="decktutor_price" data-align="center" data-sortable="true">DeckTutor</th>
			            <th data-field="ebay_price" data-align="center" data-sortable="true">Ebay</th>
			            <th data-field="lurkone_price" data-align="center" data-sortable="true">Lurkone</th>
			            <th data-field="magicorner_price" data-align="center" data-sortable="true">Magic Corner</th>
			            <th data-field="magicmarket_price" data-align="center" data-sortable="true">Magic Market</th>
			        </tr>
			    </thead>
			</table>
        	
        	
        	
        </div>
	</div>
	
	<!-- area statistiche del portale -->
	<div class="row">
		<div class="page-header">
			<h1><?=PRICE_PONDER_STATS_TITLE?></h1>
		</div>
        <div class="col-sm-4">
         <div class="row">
	          <div class="panel panel-default">
	            <div class="panel-heading">
	              <h3 class="panel-title"><?=PRICE_PONDER_STATS?></h3>
	            </div>
	            <div class="list-group">
	            <a class="list-group-item"><?=PRICE_PONDER_SETS?>: <strong><?=$tot_sets?></strong> <span class="badge badge-success"><?php echo $last_set; ?></span></a>
	            <a class="list-group-item"><?=PRICE_PONDER_CARDS?>: <strong><?=$tot_cards?></strong></a>
	            <a class="list-group-item"><?=PRICE_PONDER_SITE?>: <strong><?=PRICE_PONDER_TOT_SITE?></strong></a>
	            </div>
	          </div>
	          <?php if (status_project != 'sviluppo') {?>
	          <div class="panel panel-default">
	            <div class="panel-heading">
	              <h3 class="panel-title"><?=PRICE_PONDER_SPOT?></h3>
	            </div>
	            <center>
	            
	            <script type="text/javascript">
				/* <![CDATA[ */
				document.write('<s'+'cript type="text/javascript" src="http://ad.altervista.org/js.ad/size=300X250/r='+new Date().getTime()+'"><\/s'+'cript>');
				/* ]]> */
				</script>
				</center>
	          </div>
	          <?php } ?>
          </div>
        </div>
       
        <div class="col-sm-8">
	        <div class="well">
	        	<p><?=PRICE_PONDER_HOW_TO?></p>
	      	</div>
        </div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			 <div class="panel panel-default">
	            <div class="panel-heading">
			The information presented on this site about Magic: The Gathering, both literal and graphical, is copyrighted by Wizards of the Coast.<br /> This website is not produced, endorsed, supported, or affiliated with Wizards of the Coast.
				</div>
			</div>
		</div>
	</div>
  </div>		
  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <!--   <script src="https://code.jquery.com/jquery.js"></script> -->
    <script src="<?php echo base_url; ?>js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url; ?>js/bootstrap-typeahead.js"></script>
    <script src="<?php echo base_url; ?>js/bootstrap-table.js"></script>
    <script src="<?php echo base_url; ?>js/Chart.js"></script>
    <script src="<?php echo base_url; ?>js/legend.js"></script>
    <script src="<?php echo base_url; ?>js/fileinput.js"></script>
    <script type="text/javascript">
    var colors = ["red", "blue", "green", "yellow", "brown", "black"];
    $('.typeahead').typeahead({
        source: function (query, process) {
            return $.getJSON(
                    '<?php echo base_url; ?>ajax_mtg_call.php',
                    { query: query },
                    function (data) {
                        return process(data);
                    });
        }

         
    });
   /* $('.typeahead').on('mousedown', function(e) {
    	e.preventDefault();
    });*/
    </script>
    
    
    <script>

   		 var $result = $('#events-result');
    
       	 $(function () {
        		$('#table-pagination').bootstrapTable({
	                /*
	                onAll: function (name, args) {
	                    console.log('Event: onAll, data: ', args);
	                }
	                onClickRow: function (row) {
	                    $result.text('Event: onClickRow, data: ' + JSON.stringify(row));
	                },
	                onDblClickRow: function (row) {
	                    $result.text('Event: onDblClickRow, data: ' + JSON.stringify(row));
	                },
	                onSort: function (name, order) {
	                    $result.text('Event: onSort, data: ' + name + ', ' + order);
	                },
	                onCheck: function (row) {
	                    $result.text('Event: onCheck, data: ' + JSON.stringify(row));
	                },
	                onUncheck: function (row) {
	                    $result.text('Event: onUncheck, data: ' + JSON.stringify(row));
	                },
	                onCheckAll: function () {
	                    $result.text('Event: onCheckAll');
	                },
	                onUncheckAll: function () {
	                    $result.text('Event: onUncheckAll');
	                },
	                onColumnSwitch: function (field, checked) {
	                    $result.text('Event: onSort, data: ' + field + ', ' + checked);
	                }
	                */
	            }).on('all.bs.table', function (e, name, args) {
	                /*console.log('Event:', name, ', data:', args);*/
	            }).on('click-row.bs.table', function (e, row, $element) {
		            
	            	
	            	/*if($("#card-info").length > 0) {
	            		  $("#card-info").hide();
	            	}
            		  var i = 1;
            		  $('html, body').animate({
            			    scrollTop: "0px",	            			    
            			}, 800, function () {
	            			if (i == 1) {
            				$('input#keys').val(row['name']);
            				$('form').submit();
            				i++;
	            			}
	            		});*/
	            	
	            	
	            	
	            }).on('dbl-click-row.bs.table', function (e, row, $element) {
	            	/*$result.text('Event: dbl-click-row.bs.table, data: ' + JSON.stringify(row));*/
	            }).on('sort.bs.table', function (e, name, order) {
	            	/*$result.text('Event: sort.bs.table, data: ' + name + ', ' + order);*/
	            }).on('check.bs.table', function (e, row) {
	            	/* $result.text('Event: check.bs.table, data: ' + JSON.stringify(row));*/
	            }).on('uncheck.bs.table', function (e, row) {
	            	/*$result.text('Event: uncheck.bs.table, data: ' + JSON.stringify(row));*/
	            }).on('check-all.bs.table', function (e) {
	            	/* $result.text('Event: check-all.bs.table');*/
	            }).on('uncheck-all.bs.table', function (e) {
	            	/* $result.text('Event: uncheck-all.bs.table');*/
	            }).on('column-switch.bs.table', function (e, field, checked) {
	            	/* $result.text('Event: column-switch.bs.table, data: ' +
	                    field + ', ' + checked);*/
	            });

        	
 	            
 	    });
			
        	</script>
    
    
    <script type="text/javascript">  
	  (function() {  
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;  
	    po.src = 'https://apis.google.com/js/plusone.js';  
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);  
	  })();  
	</script>  
	<script type="text/javascript">
	
	$(document).ready(function() {

		$("form").submit(function (e) {
				if($("#card-info").length > 0) {
		      		  $("#card-info").hide();
		      	}
				/*impedisco al form di avviarsi*/
		     	e.preventDefault();
		     	var i = 1;
	      		  $('html, body').animate({
	      			    scrollTop: "0px",	            			    
	      			}, 800, function () {
	      				if (i == 1) {
	      					var keys = encodeURIComponent($('input#keys').val());
	      			     	var price = [];
	      			     	var price_number = 0;
	      			     	$("#ajax-content").html('');
	      			     	$('#myModal').modal({show:true});
	      			     	$.ajax({
	      			     	      type: "POST",
	      			     	      url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      			     	      dataType: "html",
	      				     	  data: "keys=" + keys + "&action=show_card",
	      			     	      success: function(msg)
	      			     	      {
	      			     	        $("#ajax-content").append(msg);
	      			     	        $("div#progress-bar").css('width', '16%');
	      			     	        $("h4.modal-title").text("Processing... Load MagicMarket Price");
	      				     	       $.ajax({
	      						     	      type: "POST",
	      						     	      url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      						     	      dataType: "html",
	      							     	  data: "keys=" + keys + "&action=show_price&site=MagicMarket",
	      						     	      success: function(msg)
	      						     	      {
	      						     	        $("#price_list").append(msg);
	      						     	        $("div#progress-bar").css('width', '32%');
	      						     	        $("h4.modal-title").text("Processing... Load DeckTutor Price");
	      							     	       $.ajax({
	      									     	      type: "POST",
	      									     	      url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      									     	      dataType: "html",
	      										     	  data: "keys=" + keys + "&action=show_price&site=DeckTutor",
	      									     	      success: function(msg)
	      									     	      {
	      									     	        $("#price_list").append(msg);
	      									     	        $("div#progress-bar").css('width', '48%');
	      									     	        $("h4.modal-title").text("Processing... Load Ebay Price");
	      										     	       $.ajax({
	      												     	      type: "POST",
	      												     	      url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      												     	      dataType: "html",
	      													     	  data: "keys=" + keys + "&action=show_price&site=Ebay",
	      												     	      success: function(msg)
	      												     	      {
	      													     	    	 $("#price_list").append(msg);
	      														     	     $("div#progress-bar").css('width', '64%');
	      														     	     $("h4.modal-title").text("Processing... Load Magic Corner Price");
	      														     	       $.ajax({
	      																     	      type: "POST",
	      																     	      url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      																     	      dataType: "html",
	      																	     	  data: "keys=" + keys + "&action=show_price&site=MagicCorner",
	      																     	      success: function(msg)
	      																     	      {	      

	      																     	    	$("#price_list").append(msg);
	      																     	    	$("div#progress-bar").css('width', '80%');
	      																     	    	$("h4.modal-title").text("Processing... Load LurkoneShop Price");
	      																     	    	$.ajax({	     
	      																     	    		type: "POST",
	      																     	    		url: "<?php echo base_url; ?>ajax_priceponder_call.php",
	      																     	    		dataType: "html",
	      																     	    		data: "keys=" + keys + "&action=show_price&site=Lurkoneshop",
	      																     	    		success: function(msg)
	      																     	    		{

	      																     	    			$("#price_list").append(msg);
	      																		     	        $("div#progress-bar").css('width', '100%');
	      																		     	        $("h4.modal-title").text("Processing... Ordering Price");
	      																		     	        $("span.price").each(function () {
	      																		     	        		 price[price_number] = {};
	      																								 price[price_number]["price"] = Math.round($(this).text());
	      																								 price[price_number][1] = $(this).attr('id');
	      																								 price_number++;
	      																		     	        });
	      																			     	       console.log( JSON.stringify(price) );
	      																		     	       if (typeof price[0] !== 'undefined') {
	      																	     	       		 price.sort();
	      																		     	       		price.sort(function(a,b) {
	      																			     	       	    return a.price - b.price;
	      																			     	       	});
	      																		     	       		console.log( JSON.stringify(price) );
	      																	     	        	$('a#' + price[0][1]).toggleClass('active');
	      																		     	    	$("div#progress-bar").css('width', '0%');
	      																		     	    	$('#myModal').modal('hide');
	      																		     	       } 
	      																		     	      $("div#progress-bar").css('width', '0%');
	      																	     	    	  $('#myModal').modal('hide');
	      																     	    			
	      																     	    		},
	      																     	    		error: function()
	      																     	    		{
	      																     	    			alert("Chiamata fallita, si prega di riprovare...");
	      																     	    			$('#myModal').modal('hide');
	      																     	    		}
	      																     	    	});
	      																     	        
	      																		},
	      														     	      	error: function()
	      														     	      	{
	      														     	           alert("Chiamata fallita, si prega di riprovare...");
	      															     	       $('#myModal').modal('hide');
	      														     	      	}
	      														     	    });
	      												     	      },
	      												     	      error: function()
	      												     	      {
	      												     	           alert("Chiamata fallita, si prega di riprovare...");
	      													     	       $('#myModal').modal('hide');
	      												     	      }
	      												     	    });
	      									     	      },
	      									     	      error: function()
	      									     	      {
	      									     	        alert("Chiamata fallita, si prega di riprovare...");
	      										     	       $('#myModal').modal('hide');
	      									     	      }
	      									     	    });
	      						     	      },
	      						     	      error: function()
	      						     	      {
	      						     	        alert("Chiamata fallita, si prega di riprovare...");
	      							     	       $('#myModal').modal('hide');
	      						     	      }
	      						     	    });
	      				     	       /*$('#myModal').modal('hide');*/
	      			     	      },
	      			     	      error: function()
	      			     	      {
	      			     	        alert("Chiamata fallita, si prega di riprovare...");
	      				     	       $('#myModal').modal('hide');
	      			     	      }
	      			     	    });
            					i++;
	            			}
	            	});
		      			
		     	
		});
		$(document).ajaxComplete(function(){
			$('a[rel=popover]').popover({
			  html: true,
			  trigger: 'hover',
			  placement: 'right',
			  content: function(){return '<img src="'+$(this).data('img') + '" />';}
			}); 
		}); 
		$(document).click(function() {
			$('a[rel=popover]').popover({
				  html: true,
				  trigger: 'hover',
				  placement: 'right',
				  content: function(){return '<img src="'+$(this).data('img') + '" />';}
				}); 
		});


		$("#scopri-prezzo").click(function() {
			if($("#card-info").length > 0) {
        		  $("#card-info").hide();
        	}
    		  var i = 1;
    		  $('html, body').animate({
    			    scrollTop: "0px",	            			    
    			}, 800, function () {
        			if (i == 1) {
    				$('input#keys').val(encodeURI('<?php echo $ay_card_info[0]['name']; ?>'));
    				$('form').submit();
    				i++;
        			}
        		});
		});

	});
	</script>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-47561840-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	</body>
</html>
