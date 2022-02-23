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
   

    
	
    <!-- Bootstrap -->
    <link href="<?php echo base_url; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?php echo base_url; ?>css/bootstrap-table.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>css/custom.css" rel="stylesheet">

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
			            <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Item Operate</th>
			        </tr>
			    </thead>
			</table>
        	
        	
        	
        </div>
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
    				$('input#keys').val(row['name']);
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
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url; ?>js/bootstrap-typeahead.js"></script>
    <script src="<?php echo base_url; ?>js/bootstrap-table.js"></script>
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
