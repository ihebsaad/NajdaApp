                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addom" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generateom"><b><i class="fas fa-plus"></i> Générer OM</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">OM</th>
                        <!--<th style="">Description</th>-->
                        <th style="">Historique</th>
                        <th style="">Actions</th>
                     </tr>

                    </thead>
                    <tbody>
                    @if (isset($oms))
                    
                    @foreach($oms as $om)
                        <tr>
                            <td style=";"><?php echo $om->titre; ?></td>
                            <!--<td style=";"><?php //echo $om->description; ?></td>-->
                            <td style=";">
                            <?php
                                if ($om->parent !== null)
                                {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important;" id="btnhisto" onclick="historiquedoc('.$om->parent.');"><i class="far fa-eye"></i> Voir</button>';
                                   
                                }
                                else
                                {
                                    echo "Aucun";
                                }
                            ?>
                            </td>
                            <?php 
                            $pathdoc = storage_path().$om->emplacement;
                            $templatedoc = $om->template;
                            ?>
                            <td>
                                    <div class="page-toolbar">

                                    <div class="btn-group">
                                        <?php
                                            if (stristr($om->emplacement,'annulation')=== FALSE) 
                                            {
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important;" id="btnannremp">
                                                <a style="color:black" href="#" id="annremp" onclick="remplacedoc(<?php echo $om->id; ?>,<?php echo $templatedoc; ?>);"> <i class="far fa-plus-square"></i> Annuler et remplacer</a>
                                            </button>
                                        </div>

                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important;" id="btnann">
                                                <a style="color:black"  onclick="annuledoc('<?php echo $om->titre; ?>',<?php echo $om->id; ?>,<?php echo $templatedoc; ?>);" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                            </button>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btntele">
                                                <a style="color:black" href="{{ URL::asset('storage'.'/app/'.$om->emplacement) }}" ><i class="fa fa-download"></i> Télécharger</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>