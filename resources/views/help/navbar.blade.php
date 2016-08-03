<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <i class="fa fa-question-circle fa-3x"></i>
                    <strong class="text-white">帮助中心</strong>
                </div>
                <div class="logo-element">Help</div>
            </li>
            @foreach($app_rows as $row)
            <li rel="helpapps-{{$row->id}}">
                @if(empty($row->children))
                <a href="/help/app/{{$row->id}}"> <i class="fa fa-th-large"></i>
                    <span class="nav-label">{{$row->name}}</span>
                </a>
                @else
                <a href="javascript:;"> <i class="fa fa-th-large"></i>
                    <span class="nav-label">{{$row->name}}</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    @foreach($row->children as $row)
                    <li rel="{{$row->id}}">
                        <a href="/help/app/{{$row->id}}">{{$row->version}}</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
            @foreach($news_rows as $row)
            <li rel="helpnews">
                @if(empty($row->children))
                <a href="/help/news/{{$row->id}}"> <i class="fa fa-th-large"></i>
                    <span class="nav-label">{{$row->name}}</span>
                </a>
                @else
                <a href="javascript:;"> <i class="fa fa-th-large"></i>
                    <span class="nav-label">{{$row->name}}</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    @foreach($row->children as $row)
                    <li rel="{{$row->id}}">
                        <a href="/help/news/{{$row->id}}">{{$row->name}}</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</nav>
