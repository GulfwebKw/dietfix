<aside id="sidebar_main">

    <div class="sidebar_main_header" style="padding-top:20px;">
        <div class="sidebar_logo">
            <a href="/" class="sSidebar_hide sidebar_logo_large">
                <img class="logo_regular" src="/assets/img/logocms.png" alt="" height="47" width="186"/>
                <img class="logo_light" src="/assets/img/logo_main_white.png" alt="" height="15" width="71"/>
            </a>
            <a href="" class="sSidebar_show sidebar_logo_small">
                <img class="logo_regular" src="/assets/img/logo_main_small.png" alt="" height="32" width="32"/>
                <img class="logo_light" src="/assets/img/logo_main_small_light.png" alt="" height="32" width="32"/>
            </a>
        </div>
    </div>

    <div class="menu_section">
        <ul>
            <li class="" title="">
                <a href="">
                    <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>
                    <span class="menu_title"></span>
                </a>
            </li>

            <li class="" title="">
                <a href="dietreg.php">
                    <span class="menu_icon"><i class="material-icons">&#xE87C;</i></span>
                    <span class="menu_title"></span>
                </a>
            </li>

            <li class="" title="">
                <a href="#">
                    <span class="menu_icon"><i class="material-icons">&#xE87C;</i></span>
                    <span class="menu_title"></span>
                </a>
                <ul>
                   @if(true)
                    <li class=""><a href="addedit_operator.php"></a></li>
                    @else

                    <li class=""><a href="list_operator.php"></a></li>
                    @endif
                </ul>
            </li>



            <li class="" title="Contact">
                <a href="#">
                    <span class="menu_icon"><i class="material-icons">&#xE0D0;</i></span>
                    <span class="menu_title">Contact</span>
                </a>
                <ul>

                    <li class=""><a href="">Contact Details
                        </a>
                    </li>
                    <li class=""><a href="">Contact Messages
                            <span class="uk-badge uk-badge-success uk-badge-notification"></span></a>
                    </li>
                    <li class=""><a href="">Sent Messages
                            <span class="uk-badge uk-badge-success uk-badge-notification"></span></a>
                    </li>
                </ul>
            </li>

            <li class="" title="">
                <a href="#">
                    <span class="menu_icon"><i class="material-icons">&#xE8B8;</i></span>
                    <span class="menu_title"></span>
                </a>
                <ul>
                    <li class=""><a href="list_slideshow.php" >Manage Home Slideshow</a></li>
                    <li class=""><a href="list_webgallery.php?type=membership" >Membership Gallery</a></li>
                    <li class=""><a href="list_webgallery.php?type=meal" >Meal Sample Gallery</a></li>
                    <li class=""><a href="settings.php" >Manage Basic Settings</a></li>
                    <li class=""><a href="web-message.php" >Manage Web Messages</a></li>
                    <li class=""><a href="web-seo.php" >Manage Web SEO</a></li>
                    <li class=""><a href="content.php" >Manage Single Pages</a></li>
                    <li class=""><a href="web-logs.php" >View Admin Login Logs</a></li>


                </ul>
            </li>

        </ul>

    </div>
</aside>