
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
                                              </div>
                                              <div id="jstreefld2">
                                              </div>
                                        </div>
                                        <div class="tab-pane fade  scrollable-panel" id="notestab">
                                        </div>
                                    </div>

                                </div>
            </div>
       