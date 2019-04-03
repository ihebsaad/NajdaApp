
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
                                                    <a href="#" class="btn btn-default btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-success btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-danger btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                            </div>
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
                                                      //print_r($notifications);
                                                      /*foreach ($notifications as $ntf) {
                                                        echo "<li  class='jstree-open' id='prt_".$ntf[0]['dossier']."'>".$ntf[0]['dossier']."<ul>";
                                                        foreach ($ntf as $n) {
                                                          
                                                          switch ($n['type']) {
                                                              case "email":
                                                                  echo '<li rel="tremail" ><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-envelope"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                  break;
                                                              case "fax":
                                                                  echo '<li rel="trfax" ><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-fax"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                  break;
                                                              case "tel":
                                                                  echo '<li rel="trfax" ><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-phone"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                  break;
                                                              default:
                                                                  echo '<li rel="tremail" ><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"> '.$n['sujet'].'</span></a></li>'; 
                                                          }
                                                        }
                                                        echo '</ul>'; 
                                                      }
                                                      echo '</li>'; */
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
       