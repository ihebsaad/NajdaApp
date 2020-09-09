@extends('layouts.supervislayout')

@section('content')

         <div id="mainc" class=" row" style="padding:30px 30px 30px 30px  ">
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div><br />
         @endif

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">

        {{ Session::get('success') }}
        </div>

    @endif

      <ul id="tabs" class="nav  nav-tabs"  >
                <li class=" nav-item ">
                    <a class="nav-link    " href="{{ route('supervision') }}"  >
                        <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('affectation') }}"  >
                        <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link " href="{{ route('missions') }}"  >
                        <i class="fas fa-lg  fa-user-cog"></i>  Missions
                    </a>
                </li>
                 <li class="nav-item ">
                    <a class="nav-link" href="{{ route('Calendriermissions7') }}"  >
                        <i class="fas fa-lg  fa-user-cog"></i>  Calendrier Missions
                    </a>
                </li>
                <li class="nav-item active ">
                    <a class="nav-link " href="{{ route('actionsactives30min') }}"  >
                        <i class="fas fa-lg  fa-user-cog"></i> Actions actives depuis 30 minutes
                    </a>
                  </li>

                <li class="nav-item ">
                    <a class="nav-link " href="{{ route('notifs') }}"  >
                        <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                    </a>
                </li>
       </ul>

       <!-- version affichage semaine par colonne-->





     <!-- Fin version affichage semaine par colonne-->

       <div class="uper">
        <div class="portlet box grey">
             <div class="row">
                <div class="col-lg-8"> <h4>Actions actives depuis au moins 30 minutes</h4></div>
                <div class="col-lg-4">
                   <!-- <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>-->
             

                </div>
            </div>
        </div>

        <!-- debut recherche avancee sur dossiers-->


       

            <table class="table table-striped" id="mytable" style="width:100%">
            <thead >
             <tr id="headtable">
                <th style="width:20%">Action</th>
                <th style="width:20%">Type Mission</th>
                 <th style="width:25%">extrait mission</th>
                 <th style="width:20%">Dossier</th>
             </tr>
            <tr>
               <th style="width:20%">Action</th>
               <th style="width:20%">Type Mission</th>
               <th style="width:25%">extrait mission</th>
               <th style="width:20%">Dossier</th>
            </tr>
            </thead>
            <tbody>

             <?php $actions =App\ActionEC::where('statut', 'active')->orderBy('created_at', 'desc')->get(); ?>
             @foreach($actions as $aa)
             <?php 
                      $format = "Y-m-d H:i:s";
                      $dtc = (new \DateTime())->format('Y-m-d H:i:s');
                      $dtb  = \DateTime::createFromFormat($format,$aa->date_deb)->modify('+ 30 minutes');
                      $datesys=\DateTime::createFromFormat($format, $dtc );


             ?>
             @if($datesys >= $dtb)
             <tr>
                    <td style="width:20%"><small><a  href="{{url('dossier/Mission/TraitementAction/'.$aa->Mission->dossier->id.'/'.$aa->mission_id.'/'.$aa->id)}}">
                   {{$aa->titre}} </a></small></td>
                    <td style="width:25%">
                        <?php echo '<small>'.$aa->Mission->nom_type_miss .'</small>';?>
                    </td>
                     <td style="width:25%">
                        <?php echo '<small>'.$aa->Mission->titre.'</small>';?>
                    </td>
                    <td style="width:20%"><a href="{{action('DossiersController@view', $aa->Mission->dossier->dossier_id)}}" ><?php echo App\Dossier::where('id',$aa->Mission->dossier->id)->first()->reference_medic  ?></a> <a style="color:#a0d468" href="{{action('DossiersController@fiche', $aa->Mission->dossier->dossier_id )}}" >Fiche<i class="fa fa-file-txt"/></a></td>

              </tr>
              @endif
              @endforeach
           
            </tbody>
        </table>

            <!-- fin recherche avancee sur dossiers-->

         
    </div>

<style>
     h2{background-color: grey;color:white;height: 40px;padding-top:5px;}
        h2 small{color:#FCFBFB;}

    /************** SEVEN Columns *****************************/
        @media (min-width: 768px){
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1  {
                width: 100%;
                *width: 100%;
            }
        }

        @media (min-width: 992px) {
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1 {
                width: 14.285714285714285714285714285714%;
                *width: 14.285714285714285714285714285714%;
            }
        }

        /**
         *  The following is not really needed in this case
         *  Only to demonstrate the usage of @media for large screens
         */
        @media (min-width: 1200px) {
            .seven-cols .col-md-1,
            .seven-cols .col-sm-1,
            .seven-cols .col-lg-1 {
                width: 14.285714285714285714285714285714%;
                *width: 14.285714285714285714285714285714%;
            }
        }

      /***class om***/

        /***  BIG ***/

        @media tv    {
            .om{width:270px;}

        }

        @media (min-width: 1024px) {
            .om{width:200px;}

        }



        /****  S M A L L  ---  D E V I C E S ****/
        @media  (max-width: 1280px)  /*** 150 % ***/  {
            .om{width:150px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .om i {display:none;}

        }

        /**************/
        @media (max-width: 1024px) /***     ***/  {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }


        @media (max-width: 1100px) /*** 175 % ***/  {

            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/********/


        @media (min-width: 768px) and (max-width: 980px) {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/**/

        @media (min-width: 480px) and (max-width: 767px) {
            .om{width:135px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;}
            .om i {display:none;}

        }/************/
    </style>

 @endsection
 