<?php


use \App\Http\Controllers\ActualitesController;
$liste= ActualitesController:: Liste();
$total= ActualitesController:: NbrActus();

//print_r($dossiers);

?>
@if( Route::current()->getName() !='dossiers.manage' )
@isset ($liste)
<?php if ($total >0) { ?>
<div class="news" style="padding-left:80px;padding-right:80px">
				<div class="breaking-news-ticker" id="newsTicker1">
			    	<div class="bn-news">
			    		<ul>
							<?php
							foreach($liste as $l)
							    {
                                  echo '  <li><a href="#">'.$l['description'].'</a></li>';

								}
							    ?>
			    	<!--		<li><a href="#">1.1. There many variations of passages of Lorem Ipsum available</a></li>
			    			<li><a href="#">1.2. Ipsum is simply dummy text of the printing and typesetting industry</a></li>
			    			<li><a href="#">1.3. Lorem Ipsum is simply dummy text of the printing and typesetting industry</a></li>
			    			<li><a href="#">1.4. Lorem simply dummy text of the printing and typesetting industry</a></li>
			    			<li><a href="#">1.5. Ipsum is simply dummy of the printing and typesetting industry</a></li>
			    			<li><a href="#">1.6. Lorem Ipsum simply dummy text of the printing and typesetting industry</a></li>
			    			<li><a href="#">1.7. Ipsum is simply dummy text of the printing typesetting industry</a></li>-->
			    		</ul>
			    	</div>
			    	<div class="bn-controls">
			    		<button><span class="bn-arrow bn-prev"></span></button>
			    		<button><span class="bn-action"></span></button>
			    		<button><span class="bn-arrow bn-next"></span></button>
			    	</div>
				</div>
				</div>


			<!--	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
				<script src="./breaking-news-ticker.min.js"></script>-->
				<script src="{{  URL::asset('public/js/breaking-news-ticker.min.js') }}" type="text/javascript"></script>
				<link href="{{ URL::asset('public/css/breaking-news-ticker.css') }}" rel="stylesheet">


				<script type="text/javascript">

                    jQuery(document).ready(function($){
                        $('#newsTicker1').breakingNews();


                        $('#newsTicker13').breakingNews({
                            effect: 'slide-right'
                        });



                    });

				</script>

<?php } ?>

@endisset

@endif
