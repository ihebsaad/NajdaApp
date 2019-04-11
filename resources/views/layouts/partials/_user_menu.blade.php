<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">

         <i  style="color:#b6cce7" class="fas fa-user-circle fa-5x myuser"></i>


    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
            <i style="color:white" class="far fa-user  fa-3x"></i><br>
           <?php $user = auth()->user();
                $name=$user->name;
                $iduser=$user->id;
            ?>

            <p><?php echo $name; ?></p>
        </li>
        <li style="margin-top:8px">
            <a href="{{ route('profile',$iduser) }}">
                <i class="fas fa-fw fa-lg fa-id-badge"></i>
                Mon profil
            </a>
        </li>
        <li role="presentation" class="divider"></li>
         <li style="margin-bottom:8px">
            <a href="{{ route('logout') }}">
                <i class="fas fa-fw fa-lg fa-id-badge"></i>
                Déconnexion
            </a>
        </li>

    </ul>
</li>

<style>
   /* .myuser:hover{color:black;}*/
   .navbar-nav>.user-menu>.dropdown-menu>li.user-header
   {width:160px!important;height:90px!important;}
   .dropdown-menu li a :hover{background-color: transparent;color:white;}
</style>