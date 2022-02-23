<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebApplication"> 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani</title>
	<meta name="description" content="Price Ponder - Cerca il miglior prezzo delle carte di Magic controllando i migliori siti di vendita di carte online italiani" />

	<!-- Google Authorship and Publisher Markup -->
	<link rel="author" href="https://plus.google.com/u/0/104665814574504531350/posts"/>
	<link rel="publisher" href=�https://plus.google.com/u/0/104665814574504531350/"/>
	
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
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

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
	  <nav class="navbar navbar-default" role="navigation">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header col-sm-2 col-md-2">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	      <span class="sr-only">Toggle navigation</span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="index.php"><?=PROJECT_NAME?></a>
	  </div>
	
	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <div class="col-sm-3 col-md-3">
	    <ul class="nav navbar-nav">
	      <li class="active"><a href="index.php">Home</a></li>
	  	<li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Social <b class="caret"></b></a>
	        <ul class="dropdown-menu">
	          <li class="social"><g:plusone annotation="inline" width="150"></g:plusone></li>
	          <li class="divider"></li>
	          <li class="social">
	          	<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Flocalhost%2Fpriceponder%2F&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=575243829187067" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width:100%;" allowTransparency="true"></iframe>
	          </li>
	          <li class="divider"></li>
	          <li class="social"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
	        </ul>
	      </li> 
	    </ul>
	    </div>
	  </div><!-- /.navbar-collapse -->
	</nav>
	
	
	
	
	
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
	            <a class="list-group-item"><?=PRICE_PONDER_SETS?>: <strong><?=$tot_sets?></strong></a>
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
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script type="text/javascript">
   var primo = 1;
    $('.typeahead').typeahead({
    	minLength: 5,
    	autoSelect: false,
    	showHintOnFocus: false,
    	offset:200,
    	/*remote: {
        	url: 'ajax_mtg_call.php?query=%QUERY',
    		ttl: 1 // 20 seconds to refresh the prefetched list
        },*/
        source: function (query, process) {
           
    	
            return $.getJSON(
                    'ajax_mtg_call.php',
                    { query: query },
                    function (data) {
                        return process(data);
                    });
            
          
        },
        items:20,

    
    });

    
   /* $('.typeahead').on('mousedown', function(e) {
    	e.preventDefault();
    });*/
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
			
				/*impedisco al form di avviarsi*/
		     	e.preventDefault();
		     	var keys = encodeURIComponent($('input#keys').val());
		     	var price = [];
		     	var price_number = 0;
		     	$("#ajax-content").html('');
		     	$('#myModal').modal({show:true});
		     	$.ajax({
		     	      type: "POST",
		     	      url: "ajax_priceponder_call.php",
		     	      dataType: "html",
			     	  data: "keys=" + keys + "&action=show_card",
		     	      success: function(msg)
		     	      {
		     	        $("#ajax-content").append(msg);
		     	        $("div#progress-bar").css('width', '16%');
		     	        $("h4.modal-title").text("Processing... Load MagicMarket Price");
			     	       $.ajax({
					     	      type: "POST",
					     	      url: "ajax_priceponder_call.php",
					     	      dataType: "html",
						     	  data: "keys=" + keys + "&action=show_price&site=MagicMarket",
					     	      success: function(msg)
					     	      {
					     	        $("#price_list").append(msg);
					     	        $("div#progress-bar").css('width', '32%');
					     	        $("h4.modal-title").text("Processing... Load DeckTutor Price");
						     	       $.ajax({
								     	      type: "POST",
								     	      url: "ajax_priceponder_call.php",
								     	      dataType: "html",
									     	  data: "keys=" + keys + "&action=show_price&site=DeckTutor",
								     	      success: function(msg)
								     	      {
								     	        $("#price_list").append(msg);
								     	        $("div#progress-bar").css('width', '48%');
								     	        $("h4.modal-title").text("Processing... Load Ebay Price");
									     	       $.ajax({
											     	      type: "POST",
											     	      url: "ajax_priceponder_call.php",
											     	      dataType: "html",
												     	  data: "keys=" + keys + "&action=show_price&site=Ebay",
											     	      success: function(msg)
											     	      {
												     	    	 $("#price_list").append(msg);
													     	     $("div#progress-bar").css('width', '64%');
													     	     $("h4.modal-title").text("Processing... Load Magic Corner Price");
													     	       $.ajax({
															     	      type: "POST",
															     	      url: "ajax_priceponder_call.php",
															     	      dataType: "html",
																     	  data: "keys=" + keys + "&action=show_price&site=MagicCorner",
															     	      success: function(msg)
															     	      {	      

															     	    	$("#price_list").append(msg);
															     	    	$("div#progress-bar").css('width', '80%');
															     	    	$("h4.modal-title").text("Processing... Load LurkoneShop Price");
															     	    	$.ajax({	     
															     	    		type: "POST",
															     	    		url: "ajax_priceponder_call.php",
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
