<?php

//recupero tutti i SET presenti in gioco
$ay_sets = $cd_mtg_sets->get_data(table_sets, 'releaseDate', 'DESC', '1000', '0');



?>

<nav class="navbar navbar-default" role="navigation">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header col-sm-2 col-md-2">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	      <span class="sr-only">Toggle navigation</span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="<?php echo base_url;?>index.php"><?=PROJECT_NAME?></a>
	  </div>
	
	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <div class="col-sm-10 col-md-10">
	    <ul class="nav navbar-nav">
	      <li class="active"><a href="<?php echo base_url;?>index.php">Home</a></li>
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
	     <li class="dropdown">
			<a href="#" data-toggle="dropdown" class="dropdown-toggle">Mtg Set <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<?php if (is_array($ay_sets)) { 
					foreach ($ay_sets as $set) {
						?>
						<li><a href="<?php echo base_url."set/".$set['id'].".html"?>"><?php echo $set['name']; ?></a></li>
						<?php
					}
				}?>
				
			</ul>
		</li>
		<?php 
		
			if ((isset($ay_set_cards)) AND (count($ay_set_cards)>0)) {
				?>
				
				<li class="dropdown">
			<a href="#" data-toggle="dropdown" class="dropdown-toggle">Card List <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<?php if (is_array($ay_set_cards)) { 
					foreach ($ay_set_cards as $set_card) {
						?>
						<li><a href="<?php echo base_url."card/".$set_card['id'].".html"?>"><?php echo $set_card['name']; ?></a></li>
						<?php
					}
				}?>
				
			</ul>
		</li>
				
				<?php
				
			}
		
		?>
	    </ul>
	    </div>
	  </div><!-- /.navbar-collapse -->
	</nav>