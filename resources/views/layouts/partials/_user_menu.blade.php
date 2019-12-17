<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">

        <?php      $user = auth()->user();
        if ($user->user_type=='admin') { ?>
            <i  style="color:#000000" class="fas fa-user-circle fa-4x myuser"></i>

            <?php   }else{ ?>
            <i  style="color:#b6cce7" class="fas fa-user-circle fa-4x myuser"></i>

        <?php } ?>

    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <i style="color:white;font-size:28px;margin-bottom:5px" class="far fa-user   "></i><br>
           <?php $user = auth()->user();
            $name=$user->name;
            $lastname=$user->lastname;
                $iduser=$user->id;
                $type=$user->user_type;
            ?>

            <b style="font-size: 16px;color:white;">   <?php echo $name .' '. $lastname; ?></b>
			<?php    $seance =  DB::table('seance')
            ->where('id','=', 1 )->first();
        $disp=$seance->dispatcheur ;
                $supmedic=$seance->superviseurmedic ;
                $suptech=$seance->superviseurtech ;
                $charge=$seance->chargetransport ;
                $veilleur=$seance->veilleur ;
                ?>
                </li><li class=" " style="background-color:grey;color:white;text-align:center">
<?php
        $iduser=Auth::id();
        if ($iduser==$disp) { ?>
		<span>(dispatcheur)</span><br>
		<?php }    if ($iduser==$supmedic) { ?>
                <span>(superviseur medical)<br></span>
                 <?php }
                 if ($iduser==$suptech) { ?>
                <span>(superviseur technique)<br></span>
                <?php }    if ($iduser==$charge) { ?>
                <span>(chargé transport)<br></span>
                <?php }
                    if ($iduser==$veilleur) { ?>
    <span>(Veilleur)<br></span>
    <?php } ?>
        </li>
        <li style="margin-top:8px">
            <a href="{{ route('profile',$iduser) }}">
                <i class="fas fa-fw fa-lg fa-id-badge"></i>
                Mon profil
            </a>
        </li>
        <?php if($type!='financier' && $type!='bureau'){ ?>
        <li style="margin-top:8px">
            <a href="{{ route('roles') }}">
                <i class="fas fa-fw fa-lg fa-exchange-alt"></i>
                Changer de rôle(s)
            </a>
        </li>
        <?php } ?>
<!--
        <li role="presentation" class="divider"></li>
         <li style="margin-bottom:8px">
            <a href="{{ route('changerposte') }}">
                <i class="fas fa-fw fa-lg fa-sign-out-alt"></i>
                Changer le Poste(en cours)
            </a>
        </li>
-->
    </ul>
</li>

<style>
   /* .myuser:hover{color:black;}*/
   .dropdown-menu {
    box-shadow: 0 6px 6px rgba(0, 0, 0, 0.3)!important;}
    .dropdown-menu > li > a:hover {
    background: white!important;
    color:#a9a9a9;
}
   .navbar-nav>.user-menu>.dropdown-menu>li.user-header
   {height:90px!important;}
   .navbar-nav > li > .dropdown-menu {
    left: -98px;}
   .dropdown-menu li a :hover{background-color: transparent;color:white;}
</style>