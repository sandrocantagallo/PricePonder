<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebApplication"> 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    	
    <title>Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?></title>
	<meta name="description" content="Price Ponder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>" />
   
   	<!-- Google Authorship and Publisher Markup -->
	<link rel="author" href="https://plus.google.com/u/0/104665814574504531350/posts"/>
	<link rel="publisher" href="https://plus.google.com/u/0/104665814574504531350/"/>
	
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="PricePonder">
	<meta itemprop="description" content="Price Ponder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>">
	<meta itemprop="image" content="http://localhost/priceponder/images/priceponder.jpg">
	
	<!-- Twitter Card data -->
	<meta name="twitter:card" content="Price Ponder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>">
	<meta name="twitter:site" content="@Risparmiamo">
	<meta name="twitter:title" content="PricePonder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>">
	<meta name="twitter:description" content="Price Ponder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>">
	<meta name="twitter:creator" content="@Risparmiamo">
	<!-- Twitter summary card with large image must be at least 280x150px -->
	<meta name="twitter:image:src" content="http://localhost/priceponder/images/priceponder.jpg">
	
	<!-- Open Graph data -->
	<meta property="og:title" content="Price Ponder" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="http://localhost/priceponder/" />
	<meta property="og:image" content="http://localhost/priceponder/images/priceponder.jpg" />
	<meta property="og:description" content="Price Ponder - Tutte le carte del set: <?php echo $ay_set[0]['name']; ?> Avente codice <?php echo $ay_set[0]['code']; ?> - Rilasciato il <?php echo $ay_set[0]['releaseDate']; ?> - Tipo di set: <?php echo $ay_set[0]['type']; ?>" />
	<meta property="og:site_name" content="PricePonder" />
	<meta property="article:section" content="Magic The Gathering" />
	<meta property="article:tag" content="Magic The Gathering" />
	<meta property="fb:admins" content="1521053199" /> 
   

    
	
    <!-- Bootstrap -->
    <link href="<?php echo base_url; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-table.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/custom.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/demo.css" rel="stylesheet">

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
  	  <?php 
  	  	//recupero un array con tutte le carte di questo SET
	  	  $cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	  	  $cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);
	  	  
	  	  //recupero tutte le carte del set
	  	  $ay_set_cards = $cd_mtg_sets->get_set_cards($ay_set[0]['id']);
  	  ?>
	  <?php require_once 'tpl/tpl_menu.php';?>
	
	
	<ul class="breadcrumb">

        	<li><a href="<?php echo base_url; ?>">Home</a></li>

        	<li class="active"><a href="<?php echo base_url."set/".$ay_set[0]['id'].".html"?>"><?php echo $ay_set[0]['name']; ?></a></li>


    	</ul>
	
	
	<div id="ajax-content" class="row">
	
	</div>
  
  
  	
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
			<h1><?php echo $ay_set[0]['name']?> - Cards List</h1>
		</div>
	
	
	 <div class="col-sm-12">
        
        	<!-- tabella ultime carte recuperate -->
        <div id="events-result"></div>
        	<table id="table-pagination" data-page-size="[50]" data-page-list="[50, 100, 200]" data-show-columns="true" data-url="<?php echo base_url; ?>set_cards_json.php?id=<?php echo $id; ?>" data-toggle="table" data-height="600" data-pagination="true" data-search="true">
			    <thead>
			        <tr>
			            <!--  <th data-field="state" data-checkbox="true"></th>  -->

			            <th data-field="name" data-align="center" data-sortable="true">Nome</th>
			            <th data-field="type" data-align="center" data-sortable="true">Tipo</th>
			            <th data-field="cmc" data-align="center" data-sortable="true">Costo Mana</th>
			            <th data-field="power" data-align="center" data-sortable="true">Forza</th>
			            <th data-field="toughness" data-align="center" data-sortable="true">Costituzione</th>
			            <th data-field="manaCost" data-align="center" data-sortable="true">Costo</th>
			            <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Azioni</th>
			        </tr>
			    </thead>
			</table>
        	
        	
        	
        </div>
       <div class="row"> 
        <div class="page-header">
			<h1><?php echo $ay_set[0]['name']?> - Distribuzione Carte Colore</h1>
		</div>
        <!-- area statistica del set -->
	<div class="col-sm-4">
		<canvas id="pie-cards-color"></canvas>
		<div id="placeholder-cards-color"></div>
		
	</div>
	<div class="col-sm-8">
		
		<ul class="list-group">
            <li class="list-group-item">Totale Carte Blocco <?php echo $tot_cards_set; ?></li>
            <li class="list-group-item">Carte Bianche <?php echo $tot_w_cards; ?></li>
            <li class="list-group-item">Carte Nere <?php echo $tot_b_cards; ?></li>
            <li class="list-group-item">Carte Blu <?php echo $tot_u_cards; ?></li>
            <li class="list-group-item">Carte Verdi <?php echo $tot_g_cards; ?></li>
            <li class="list-group-item">Carte Rosse <?php echo $tot_r_cards; ?></li>
          	<li class="list-group-item">Carte Multicolore <?php echo $tot_multicolored; ?></li>
          	<li class="list-group-item">Carte Incolore <?php echo $tot_incolored; ?></li>
          </ul>
		
	</div>
	</div>
	<?php
	if (is_array($ay_curva_mana)) {
					
		?>
	    <!-- Genero il grafico con l'andamento del prezzo dell carta -->
	    <div class="row">
	    	<div class="page-header">
			<h1><?php echo $ay_set[0]['name']?> - Curva Di Mana</h1>
		</div>
	    	<div class="col-sm-12 col-md-12">
	    		<canvas id="curva-mana" style="height:450px; width:600px;"></canvas>
	    		<div id="placeholder-curva-mana"></div>
	    		
	    		
	    	</div>
	    </div>
	    <?php 
	    	}
	    ?>
        
	</div>
	
	<script>
    function operateFormatter(value, row, index) {
        return [
            '<a class="tag" href="javascript:void(0)" title="Controlla Prezzo">',
                '<i class="glyphicon glyphicon-tag"></i>',
            '</a>',
            '<a class="eye ml10" href="javascript:void(0)" title="Dettaglio Carta">',
                '<i class="glyphicon glyphicon-eye-open"></i>',
            '</a>',
        ].join('');
    }

    window.operateEvents = {
        'click .tag': function (e, value, row, index) {
            /*alert('You click like icon, row: ' + JSON.stringify(row));
            console.log(value, row, index);*/
            if($("#card-info").length > 0) {
        		  $("#card-info").hide();
        	}
    		  var i = 1;
    		  $('html, body').animate({
    			    scrollTop: "0px",	            			    
    			}, 800, function () {
        			if (i == 1) {
        				var StrippedString =row['name'].replace(/(<([^>]+)>)/ig,"");
    				$('input#keys').val(StrippedString);
    				$('form').submit();
    				i++;
        			}
        		});
        },
        'click .eye': function (e, value, row, index) {
           /* alert('You click edit icon, row: ' + JSON.stringify(row)); */
           /* console.log(value, row, index); */
            window.location.href = "<?php echo base_url; ?>card/" + row['multiverseid'] + ".html";
        },
       
    };
</script>
	
	
	
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
    
    

    <script type="text/javascript">
		var options = {
				  legendTemplate : '<ul>'
				                  +'<% for (var i=0; i<datasets.length; i++) { %>'
				                    +'<li>'
				                    +'<span style=\"background-color:<%=datasets[i].lineColor%>\"></span>'
				                    +'<% if (datasets[i].label) { %><%= datasets[i].label %><% } %>'
				                  +'</li>'
				                +'<% } %>'
				              +'</ul>'
				  };

		var data = [
		            {
		                value: <?php echo $r_perc; ?>,
		                color:"#F7464A",
		                highlight: "#FF5A5E",
		                label: "Carte Rosse"
		            },
		            {
		                value: <?php echo $g_perc; ?>,
		                color: "#00c55a",
		                highlight: "#8bdd93",
		                label: "Carte Verdi"
		            },
		            {
		                value: <?php echo $w_perc; ?>,
		                color: "#f7fce2",
		                highlight: "#e6e9d9",
		                label: "Carte Bianche"
		            },
		            {
		                value: <?php echo $b_perc; ?>,
		                color: "#30312e",
		                highlight: "#565752",
		                label: "Carte Nere"
		            },
		            {
		                value: <?php echo $u_perc; ?>,
		                color: "#006b9a",
		                highlight: "#0876a7",
		                label: "Carte Blu"
		            },
		            {
		                value: <?php echo $m_perc; ?>,
		                color: "#9e08a7",
		                highlight: "#cb5fd2",
		                label: "Multicolore"
		            },
		            {
		                value: <?php echo $i_perc; ?>,
		                color: "#dddddd",
		                highlight: "#cccccc",
		                label: "Incolore"
		            },
		        ];
		var options2 = {
				  legendTemplate : '<ul>'
				                  +'<% for (var i=0; i<datasets.length; i++) { %>'
				                    +'<li>'
				                    +'<span style=\"background-color:<%=datasets[i].lineColor%>\"></span>'
				                    +'<% if (datasets[i].label) { %><%= datasets[i].label %><% } %>'
				                  +'</li>'
				                +'<% } %>'
				              +'</ul>'
				  }
		
  		var lineChartData2 = {
  				labels : [<?php 
  					
  					if (is_array($ay_curva_mana)) {
								foreach ($ay_curva_mana as $key=>$val) {
									echo '"'.$key++.'",';
								}
						}	
  				
  				?>],
  				datasets : [
					<?php 
							
							if (is_array($ay_curva_mana)) {
									?>
									{
			    						label: "Costo Mana",
			    						fillColor : "rgba(220,220,220,0.2)",
			    						strokeColor : "rgba(220,220,220,1)",
			    						pointColor : "rgba(220,220,220,1)",
			    						pointStrokeColor : "#fff",
			    						pointHighlightFill : "#fff",
			    						pointHighlightStroke : "rgba(220,220,220,1)",
			    						data : [
										<?php

									foreach ($ay_curva_mana as $curva_mana) {
										echo '"'.$curva_mana.'",';
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
        
			var ctx = document.getElementById('pie-cards-color').getContext('2d');
			var myPieChart = new Chart(ctx).Pie(data, {
				responsive: true,
				
			});

			legend(document.getElementById('placeholder-cards-color'), data);

			var cty = document.getElementById("curva-mana").getContext("2d");

			var myLineChart = new Chart(cty).Line(lineChartData2, {
				responsive: true,
				
			});
			
			legend(document.getElementById('placeholder-curva-mana'), lineChartData2);
			
		};
	
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
		// Add custom JS here
		
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
