<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul id="side-menu" class="nav metismenu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <img alt="image" class="img-circle" src="{{getPhoto(session('token')['user_id'])}}" width="50" />
                    </span>
                    <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold">{{session('token')['realname']}}</strong>
                            </span>
                            <span class="text-muted text-xs block">
                                欢迎您 <b class="caret"></b>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{url('admin/profile')}}"><i class="fa fa-user"></i> 个人资料</a></li>
                        <li><a href="{{url('admin/profile/changepwd')}}"><i class="fa fa-key"></i> 修改密码</a></li>
                        <li class="divider"></li>
                        <li><a href="{{url('admin/logout')}}" onclick="return confirm('您确定要退出吗？');"><i class="fa fa-sign-out"></i> 退出</a></li>
                    </ul>
                </div>
                <div class="logo-element"><i class="fa fa-bars"></i></div>
            </li>
            <li rel="welcome">
                <a href="{{url('admin')}}"> <i class="fa fa-flag"></i>
                    <span class="nav-label">欢迎您</span>
                </a>
            </li>
            @if($user->can('system'))
            <li rel="entrust">
                <a href="javascript:;">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-label">系统管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li rel="role">
                        <a href="{{url('admin/entrust/role-list')}}">角色管理</a>
                    </li>
                    <li rel="user">
                        <a href="{{url('admin/entrust/user-list')}}">用户管理</a>
                    </li>
                    <li rel="permission">
                        <a href="{{url('admin/entrust/perm-list')}}">权限管理</a>
                    </li>
                    <li rel="siteconfig">
                        <a href="{{url('admin/sitecfg/meta-set')}}">站点配置</a>
                    </li>
                </ul>
            </li>
            @endif
            @if($user->can('subject'))
                <li rel="subject">
                    <a href="{{url('admin/subject')}}"> <i class="fa fa-sort"></i>
                        <span class="nav-label">学科管理</span>
                    </a>
                </li>
            @endif
            @if($user->can('capacity'))
                <li rel="capacity">
                    <a href="{{url('admin/capacity')}}"> <i class="fa fa-plus"></i>
                        <span class="nav-label">容量申请管理</span>
                    </a>
                </li>
            @endif
            @if($user->can('file'))
                <li rel="file">
                    <a href="{{url('admin/file')}}"> <i class="fa fa-file-video-o"></i>
                        <span class="nav-label">文件发布管理</span>
                    </a>
                </li>
            @endif
            @if($user->can('app-type'))
                <li rel="apptype">
                    <a href="{{url('admin/apptype')}}"> <i class="fa fa-tag"></i>
                        <span class="nav-label">应用类型管理</span>
                    </a>
                </li>
            @endif
            <li rel="webuser">
                <a href="{{url('admin/webuser/teacher')}}"> <i class="fa fa-graduation-cap"></i>
                    <span class="nav-label">教师账号管理</span>
                </a>
            </li>
            <li rel="message">
                <a href="javascript:;">
                    <i class="fa fa-envelope"></i>
                    <span class="nav-label">消息管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li rel="msg_write">
                        <a href="{{url('admin/message/write')}}">写消息</a>
                    </li>
                    <!-- <li rel="msg_inbox">
                        <a href="{{url('admin/message/inbox')}}">收件箱</a>
                    </li> -->
                    <li rel="msg_outbox">
                        <a href="{{url('admin/message/outbox')}}">发件箱</a>
                    </li>
                    <li rel="msg_drafts">
                        <a href="{{url('admin/message/drafts')}}">草稿箱</a>
                    </li>
                    <li rel="msg_trash">
                        <a href="{{url('admin/message/trash')}}">垃圾箱</a>
                    </li>
                </ul>
            </li>
<<<<<<< HEAD
            @if($user->can('devices'))
                    <!--设备管理-->
                <li rel="devices">
                    <a href="javascript:;">
                        <i class="fa fa-video-camera"></i>
                        <span class="nav-label">编码器设备管理</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li rel="1">
                            <a href="{{url('admin/device')}}">设备管理</a>
                        </li>
                        <li rel="2">
                            <a href="{{url('admin/device/classroom')}}">教室管理</a>
                        </li>

                        </li>
                    </ul>
                </li>

            @endif
            <li rel="profile">
                <a href="javascript:;">
                    <i class="fa fa-user"></i>
                    <span class="nav-label">个人管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li rel="1">
                        <a href="{{url('admin/profile')}}">基本资料</a>
                    </li>
                    <li rel="2">
                        <a href="{{url('admin/profile/changepwd')}}">修改密码</a>
                    </li>
                </ul>
            </li>
=======
>>>>>>> 3f5bc57f1bb5751c2e394ead577112c5241fcb87
        </ul>
    </div>
</nav>