
            <div class="panel panel-default"  id="notificationspanel">
                                <div class="panel-heading" id="headernotifs">
                                    <h4 class="panel-title">Notifications</h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                    </span>
                                </div>
                                <div class="panel-body" style="display: block;">
                                    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                        <li class="active">
                                            <a href="#notificationstab" data-toggle="tab">Notifs</a>
                                        </li>
                                        <li>
                                            <a href="#notestab" data-toggle="tab">Notes</a>
                                        </li>
                                    </ul>
                                    <div id="NotificationsTabContent" class="tab-content">
                                        <div class="tab-pane fade active in  scrollable-panel" id="notificationstab">
                                            <div class="row" style="width: 99%">
                                               <div class="col-xs-9 col-md-9 align-left"> 
                                                    <div class="select">
                                                      <select>
                                                        <option>Trier par</option>
                                                        <option>Temps</option>
                                                        <option>Dossier</option>
                                                      </select>
                                                      <div class="select__arrow"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-default btn-sm btn-responsive" role="button" id='tte'> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-success btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-danger btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                            </div>
                                            @php
                                            {{ //print_r(config('commondata.dossiers')); 
                                            }}
                                            @endphp
                                            <!-- treeview of notifications -->
                                            <div id="jstree">
                                              <ul>
                                                <!-- in this example the tree is populated from inline HTML -->
                                                <!--<ul>
                                                  <li >Root node 1
                                                    <ul>
                                                      <li id="child_node_1" type="demo">Child node 1</li>
                                                      <li id="D123" type="foldernotifs">Child node 2</li>
                                                    </ul>
                                                  </li>
                                                  <li>Root node 2</li>
                                                </ul>

                                                <button id="btntree">demo button</button>-->
                                                 @php
                                                    {{
                                                      //session()->put('authuserid',Auth::id());
                                                      //$notifications = config('commondata.notifications');
                                                      $notificationns = DB::table('notifications')->where('notifiable_id','=', Auth::id() )->get()->toArray();
            
                                                      // extraire les informations de l'entree à travers id trouvé dans la notification
                                                      $nnotifs = array();
                                                      foreach ($notificationns as $i) {
                                                        $notifc = json_decode($i->data, true);
                                                        $entreeid = $notifc['Entree']['id'];
                                                        $notifentree = DB::table('entrees')->where('id','=', $entreeid)->get()->toArray();
                                                        $row = array();
                                                        $row['id'] = $entreeid;
                                                        //print_r($notifc) ;
                                                        $row['read_at']= $i->read_at;
                                                        foreach ($notifentree as $ni) {
                                                          $row['sujet'] = $ni->sujet;
                                                          $row['type'] = $ni->type;
                                                          $row['dossier'] = $ni->dossier;
                                                        }
                                                        $nnotifs[] = $row;
                                                      }

                                                      // group notifications by ref dossier
                                                      $result = array();
                                                      foreach ($nnotifs as $element) {
                                                          if (isset($element['dossier']))
                                                          { $result[$element['dossier']][] = $element; }
                                                          else
                                                          {
                                                            $result[null][] = $element;
                                                          }
                                                      } 
                                                      $notifications = $result;




                                                      foreach ($notifications as $ntf) {
                                                        if (!empty($ntf[0]['dossier']))
                                                        {
                                                          // recuperation nom assuré du dossier
                                                            $search = $ntf[0]['dossier'];
                                                            $assuree = $dossiers->filter(function($item) use ($search) {
                                                                return stripos($item['reference_medic'],$search) !== false;

                                                            });

                                                            $nassure =$assuree->first();
                                                          // fin recuperation nom assuré
                                                          echo "<li  class='jstree-open' id='prt_".$ntf[0]['dossier']."'>".$ntf[0]['dossier']." | ".$nassure['subscriber_name']." ".$nassure['subscriber_lastname']."<ul>";}
                                                        foreach ($ntf as $n) {
                                                          
                                                          if (!isset ($n['type']) )
                                                          {  $n['type'] = 'default'; }

                                                           
                                                            if ((empty($n['read_at']))||(is_null($n['read_at'])))
                                                              { $newnotif=" class='newnotif'" ;}
                                                            else
                                                              {$newnotif="" ;}

                                                          if (!isset ($n['sujet']) )
                                                            {  $n['sujet'] = ' '; }

                                                            switch ($n['type']) {
                                                                case "email":
                                                                    echo '<li id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-envelope"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "fax":
                                                                    echo '<li id="'.$n['id'].'" rel="trfax" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-fax"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "tel":
                                                                    echo '<li id="'.$n['id'].'" rel="trtel" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-phone"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "sms":
                                                                    echo '<li id="'.$n['id'].'" rel="trsms" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fas fa-sms"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "whatsapp":
                                                                    echo '<li id="'.$n['id'].'" rel="trwp" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fab fa-whatsapp"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                default:
                                                                    echo '<li id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"> '.$n['sujet'].'</span></a></li>'; 
                                                            }


                                                        }
                                                        if (!empty($ntf[0]['dossier'])) {echo '</ul>'; }
                                                      }
                                                      if (!empty($ntf[0]['dossier'])) {echo '</li>';}
                                                    }}
                                                  @endphp
                                              </ul>
                                              </div>
                                                 
                                        </div>
                                        <div class="tab-pane fade  scrollable-panel" id="notestab">
                                        </div>
                                    </div>

                                </div>
            </div>
<?php
if (isset($dossier))
{
?>
<script type="text/javascript">
         $( document ).ready(function() {
            // verifier sil existe des notifications pour le dossier courant pour les marquer comme actifs
            if ($("#prt_{{ $dossier['reference_medic']}}").length > 0) { 
              // $("li#prt_{{{-- $dossier['reference_medic'] --}}}").addClass('dossiercourant');
               // scroll vers lemplacement de la notification
              $('html, #notificationstab').animate({
               scrollTop: $("#prt_{{ $dossier['reference_medic']}}").offset().top
              }, 1000);
              
            }
          });
</script>
<?php } ?>