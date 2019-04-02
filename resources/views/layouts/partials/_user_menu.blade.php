<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle padding-user" data-toggle="dropdown">

            <img src="{{   URL::asset('public/img/qvLWCpbhZp.jpg') }}" alt="img"
                 class="img-circle img-responsive pull-left" height="100" width="100" style="width: 60px; height: 60px;" />

        <div class="riot">
        </div>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
                <img src="{{   URL::asset('public/img/qvLWCpbhZp.jpg') }}" alt="img"
                     class="img-circle img-bor"/>
            <p>Agent Najda</p>
        </li>
        <li role="presentation"></li>
        <li>
            <a href="#">
                <i class="fa fa-fw fa-user"></i>
                Mon profil
            </a>
        </li>
        <li role="presentation" class="divider"></li>
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
            <a href="#">
                <i class="fa fa-fw fa-retweet"></i>
                Rôles
            </a>
            </div>
            <div class="pull-right">
                <a   href="{{ route('logout') }}">
                    <i class="fa fa-fw fa-sign-out"></i>
                    Se déconnecter
                </a>
            </div>
        </li>
    </ul>
</li>