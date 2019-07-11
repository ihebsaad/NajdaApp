<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">

         <i  style="color:#b6cce7" class="fas fa-user-circle fa-4x myuser"></i>


    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <i style="color:white" class="far fa-user  fa-3x"></i><br>
           <?php $user = auth()->user();
                $name=$user->name;
                $iduser=$user->id;
            ?>
            <p>
			<?php echo $name; ?>
			<?php    $seance =  DB::table('seance')
            ->where('id','=', 1 )->first();
        $disp=$seance->dispatcheur ;
        $sup=$seance->superviseur ;

        $iduser=Auth::id();
        if ($iduser==$disp) { ?>
		<span>(le dispatcheur)</span>
		<?php }    if ($iduser==$sup) { ?>
		<span>(le superviseur)</span>
		<?php } ?>
		</p>
        </li>
        <li style="margin-top:8px">
            <a href="{{ route('profile',$iduser) }}">
                <i class="fas fa-fw fa-lg fa-id-badge"></i>
                Mon profil
            </a>
        </li>
        <li style="margin-top:8px">
            <a href="{{ route('roles') }}">
                <i class="fas fa-fw fa-lg fa-exchange-alt"></i>
                Changer de rôle(s)
            </a>
        </li>
        <li role="presentation" class="divider"></li>
         <li style="margin-bottom:8px">
            <a href="{{ route('logout') }}">
                <i class="fas fa-fw fa-lg fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </li>

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