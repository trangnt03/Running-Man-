<?php
/*
 * Plugin Name: STT - Plugin Trang trí Tết
 * Plugin URI: https://sharethuthuat.com
 * Version: 1.0.1
 * Description: Trang trí Tết Việt Nam bằng câu đối, hoa đào, hoa mai, pháo hoa và các hỉnh ảnh tượng trưng cho ngày Tết truyền thống của Việt Nam
 * Author: Share Thủ Thuật
 * Author URI: https://sharethuthuat.com
 * Text Domain: stt-tet
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !defined( 'STT_TET_BASENAME' ) )
    define( 'STT_TET_BASENAME', plugin_basename( __FILE__ ) );

if (!defined('STT_TET_URL'))
    define('STT_TET_URL', plugin_dir_url(__FILE__));

function STT_TET_imgs($img = 'style_1', $value = ''){
    $images = apply_filters('STT_TET_imgs', array(
        'style_1' => array(
            'left' => STT_TET_URL . 'images/left-1.png',
            'right' => STT_TET_URL . 'images/right-1.png',
            'left_w' => 191,
            'right_w' => 191,
        ),
        'style_2' => array(
            'left' => STT_TET_URL . 'images/left-2.png',
            'right' => STT_TET_URL . 'images/right-2.png',
            'left_w' => 148,
            'right_w' => 148,
        ),
        'style_3' => array(
            'left' => STT_TET_URL . 'images/left-3.png',
            'right' => STT_TET_URL . 'images/right-3.png',
            'left_w' => 266,
            'right_w' => 181,
        ),
        'style_4' => array(
            'left' => STT_TET_URL . 'images/left-4.png',
            'right' => STT_TET_URL . 'images/right-4.png',
            'left_w' => 148,
            'right_w' => 148,
        ),
        'bottom_1' => array(
            'url' => STT_TET_URL . 'images/bottom-1.png',
            'full' => 0,
            'w' => 320,
            'left' => 80,
        ),
        'bottom_2' => array(
            'url' => STT_TET_URL . 'images/bottom-2.png',
            'full' => 1,
            'w' => 1600,
            'left' => 0,
        ),
        'dao' => array(
            'url' => STT_TET_URL . 'images/hoadao.png',
            'w' => 15
        ),
        'mai' => array(
            'url' => STT_TET_URL . 'images/hoamai.png',
            'w' => 30
        ),
    ));
    if($value){
        return isset($images[$img][$value]) ? esc_attr($images[$img][$value]) : '';
    }
    return isset($images[$img]) ? (array) $images[$img] : '';
}

add_action('wp_footer', 'STT_TET', 999);
function STT_TET(){
    $style = esc_attr(devvn_get_tet_holiday_options('style'));
    $left_width = intval(STT_TET_imgs($style, 'left_w'));
    $right_width = intval(STT_TET_imgs($style, 'right_w'));
    $zindex = intval(devvn_get_tet_holiday_options('zindex'));
    $container_width = intval(devvn_get_tet_holiday_options('container_width'));

    $bottom_style = esc_attr(devvn_get_tet_holiday_options('bottom_style'));
    $bottom_full = intval(STT_TET_imgs($bottom_style, 'full'));
    $bottom_left = intval(STT_TET_imgs($bottom_style, 'left'));
    $bottom_w = $bottom_full ? '100%' : intval(STT_TET_imgs($bottom_style, 'w')).'px';

    $enable_firework = intval(devvn_get_tet_holiday_options('enable_firework'));
    $enable_hoamaidao = esc_attr(devvn_get_tet_holiday_options('enable_hoamaidao'));
    $enable_audio = intval(devvn_get_tet_holiday_options('enable_audio'));

    if($style != 'custom') {
        $left_url = STT_TET_imgs($style, 'left');
        $right_url = STT_TET_imgs($style, 'right');
    }else{
        $left_banner = devvn_get_tet_holiday_options('left_banner');
        $right_banner = devvn_get_tet_holiday_options('right_banner');

        $left_url = wp_get_attachment_image_url($left_banner, 'full');
        $right_url = wp_get_attachment_image_url($right_banner, 'full');

        $left_width = wp_get_attachment_metadata($left_banner);
        $left_width = ($left_width && !is_wp_error($left_width) && is_array($left_width) && $left_width['width']) ? $left_width['width'] : '';

        $right_width = wp_get_attachment_metadata($right_banner);
        $right_width = ($right_width && !is_wp_error($right_width) && is_array($right_width) && $right_width['width']) ? $right_width['width'] : '';
    }
    ?>
    <style type="text/css">
        .tet_left img, .tet_right img {
            width: 100%;
            height: auto;
        }
        .tet_left, .tet_right {
            position: fixed;
            top: 0;
            left: 0;
            z-index: <?php echo esc_attr($zindex);?>;
            width: <?php echo esc_attr($left_width);?>px;
            pointer-events: none;
        }
        .tet_right {
            left: auto;
            right: 0;
            width: <?php echo esc_attr($right_width);?>px;
        }
        <?php if(!$bottom_full):?>
        .tet_bottom {
            position: fixed;
            bottom: 0;
            left: <?php echo esc_attr($bottom_left);?>px;
            z-index: <?php echo esc_attr($zindex);?>;
            width: <?php echo esc_attr($bottom_w);?>;
            pointer-events: none;
        }
        <?php else:?>
        .tet_bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: <?php echo esc_attr($zindex);?>;
            width: 100%;
            height: 120px;
            background: url("<?php echo esc_url(STT_TET_imgs($bottom_style, 'url'));?>") repeat-x;
            background-size: auto 100% !important;
            pointer-events: none;
        }
        <?php endif;?>
        @media (max-width: <?php echo esc_attr(($container_width+$left_width));?>px){
            .tet_left, .tet_right, .tet_bottom{
                display: none !important;
            }
        }
    </style>
    <?php if($left_url):?><div class="tet_left"><img src="<?php echo esc_url($left_url);?>" alt=""/></div><?php endif;?>
    <?php if($right_url):?><div class="tet_right"><img src="<?php echo esc_url($right_url);?>" alt=""/></div><?php endif;?>
    <?php if($bottom_style != 'none'):?>
        <div class="tet_bottom">
            <?php if(!$bottom_full):?>
            <img src="<?php echo esc_url(STT_TET_imgs($bottom_style, 'url'));?>" alt=""/>
            <?php endif;?>
        </div>
    <?php endif;?>
    <?php if($enable_firework == 1):?>
    <script type="text/javascript">
        var boddie,bits=90,speed=<?php echo intval(devvn_get_tet_holiday_options('firework_speed'));?>,bangs=<?php echo intval(devvn_get_tet_holiday_options('firework_number'));?>,colours=new Array("#03f","#f03","#fff","#f7efa1","#0cf","#f93","#f0c","#fff"),bangheight=new Array,intensity=new Array,colour=new Array,Xpos=new Array,Ypos=new Array,dX=new Array,dY=new Array,stars=new Array,decay=new Array,swide=800,shigh=600;function write_fire(e){var t;for(stars[e+"r"]=createDiv("|",12),boddie.appendChild(stars[e+"r"]),t=bits*e;t<bits+bits*e;t++)stars[t]=createDiv("*",13),boddie.appendChild(stars[t])}function createDiv(e,t){var o=document.createElement("div");return o.style.font=t+"px monospace",o.style.position="absolute",o.style.backgroundColor="transparent",o.appendChild(document.createTextNode(e)),o}function launch(e){colour[e]=Math.floor(Math.random()*colours.length),Xpos[e+"r"]=.5*swide,Ypos[e+"r"]=shigh-5,bangheight[e]=Math.round((.5+Math.random())*shigh*.4),dX[e+"r"]=(Math.random()-.5)*swide/bangheight[e],1.25<dX[e+"r"]?stars[e+"r"].firstChild.nodeValue="/":dX[e+"r"]<-1.25?stars[e+"r"].firstChild.nodeValue="\\":stars[e+"r"].firstChild.nodeValue="|",stars[e+"r"].style.color=colours[colour[e]]}function bang(e){for(var t,o=0,n=bits*e;n<bits+bits*e;n++)(t=stars[n].style).left=Xpos[n]+"px",t.top=Ypos[n]+"px",decay[n]?decay[n]--:o++,15==decay[n]?t.fontSize="10px":7==decay[n]?t.fontSize="2px":1==decay[n]&&(t.visibility="hidden"),Xpos[n]+=dX[n],Ypos[n]+=dY[n]+=1.25/intensity[e];o!=bits&&setTimeout("bang("+e+")",speed)}function stepthrough(e){var t,o,n,i=Xpos[e+"r"],d=Ypos[e+"r"];if(Xpos[e+"r"]+=dX[e+"r"],Ypos[e+"r"]-=4,Ypos[e+"r"]<bangheight[e]){for(o=Math.floor(3*Math.random()*colours.length),intensity[e]=5+4*Math.random(),t=e*bits;t<bits+bits*e;t++)Xpos[t]=Xpos[e+"r"],Ypos[t]=Ypos[e+"r"],dY[t]=(Math.random()-.5)*intensity[e],dX[t]=(Math.random()-.5)*(intensity[e]-Math.abs(dY[t]))*1.25,decay[t]=25+Math.floor(25*Math.random()),n=stars[t],o<colours.length?n.style.color=colours[t%2?colour[e]:o]:o<2*colours.length?n.style.color=colours[colour[e]]:n.style.color=colours[t%colours.length],n.style.fontSize="20px",n.style.visibility="visible";bang(e),launch(e)}stars[e+"r"].style.left=i+"px",stars[e+"r"].style.top=d+"px"}function set_width(){var e=999999,t=999999;document.documentElement&&document.documentElement.clientWidth&&(0<document.documentElement.clientWidth&&(e=document.documentElement.clientWidth),0<document.documentElement.clientHeight&&(t=document.documentElement.clientHeight)),void 0!==self.innerWidth&&self.innerWidth&&(0<self.innerWidth&&self.innerWidth<e&&(e=self.innerWidth),0<self.innerHeight&&self.innerHeight<t&&(t=self.innerHeight)),document.body.clientWidth&&(0<document.body.clientWidth&&document.body.clientWidth<e&&(e=document.body.clientWidth),0<document.body.clientHeight&&document.body.clientHeight<t&&(t=document.body.clientHeight)),999999!=e&&999999!=t||(e=800,t=600),swide=e,shigh=t}window.onload=function(){var e;if(document.getElementById)for((boddie=document.createElement("div")).style.position="fixed",boddie.style.bottom="0px",boddie.style.top="0px",boddie.style.overflow="visible",boddie.classList.add("tet_firework"),boddie.style.width="0",boddie.style.height="0",boddie.style.backgroundColor="transparent",boddie.style.pointerEvents="none",document.body.appendChild(boddie),set_width(),e=0;e<bangs;e++)write_fire(e),launch(e),setInterval("stepthrough("+e+")",speed)},window.onresize=set_width;
    </script>
    <?php if($enable_audio):?>
        <iframe src="<?php echo STT_TET_URL;?>images/phao-hoa.mp3" allow="autoplay" style="display:none" id="iframeAudio"></iframe>
        <audio autoplay id="playAudio">
            <source src="<?php echo STT_TET_URL;?>images/phao-hoa.mp3" type="audio/mpeg">
        </audio>
        <script type="text/javascript">
            var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
            if (!isChrome){
                jQuery('#iframeAudio').remove()
            }
            else {
                jQuery('#playAudio').remove() // just to make sure that it will not have 2x audio in the background
            }
        </script>
    <?php endif;?>
    <?php endif;?>
    <?php if($enable_hoamaidao != 'none'):?>
    <script type="text/javascript">
        var no = <?php echo intval(devvn_get_tet_holiday_options('number_tet_pc'));?>;
        if (matchMedia('only screen and (max-width: 767px)').matches) {
            no = <?php echo intval(devvn_get_tet_holiday_options('number_tet_mobile'));?>
        }
        let img_url = '<?php echo esc_url(STT_TET_imgs($enable_hoamaidao, 'url'));?>';
        var hidesnowtime = 0;
        var color_snow  = '#fff';
        var snowdistance = 'windowheight'; // windowheight or pageheight;
        var ie4up = (document.all) ? 1 : 0;
        var ns6up = (document.getElementById && !document.all) ? 1 : 0;

        function iecompattest() {
            return (document.compatMode && document.compatMode != 'BackCompat') ? document.documentElement : document.body
        }

        var dx, xp, yp;
        var am, stx, sty;
        var i, doc_width = 800, doc_height = 600;
        if (ns6up) {
            doc_width = self.innerWidth;
            doc_height = self.innerHeight
        } else if (ie4up) {
            doc_width = iecompattest().clientWidth;
            doc_height = iecompattest().clientHeight
        }
        dx = new Array();
        xp = new Array();
        yp = new Array();
        am = new Array();
        stx = new Array();
        sty = new Array();
        for (i = 0; i < no; ++i) {
            dx[i] = 0;
            xp[i] = Math.random() * (doc_width - 50);
            yp[i] = Math.random() * doc_height;
            am[i] = Math.random() * 20;
            stx[i] = 0.02 + Math.random() / 10;
            sty[i] = 0.7 + Math.random();
            if (ie4up || ns6up) {
                document.write('<div id="dot'+i+'" style="POSITION:fixed;Z-INDEX:'+(<?php echo intval(devvn_get_tet_holiday_options('zindex'));?>+i)+';VISIBILITY:visible;TOP:15px;LEFT:15px;pointer-events: none;width:<?php echo intval(STT_TET_imgs($enable_hoamaidao, 'w'))?>px"><span style="font-size:18px;color:'+color_snow+'"><img src="'+img_url+'" alt=""></span></div>');
            }
        }

        function snowIE_NS6() {
            doc_width = ns6up ? window.innerWidth - 10 : iecompattest().clientWidth - 10;
            doc_height = (window.innerHeight && snowdistance == 'windowheight') ? window.innerHeight : (ie4up && snowdistance == 'windowheight') ? iecompattest().clientHeight : (ie4up && !window.opera && snowdistance == 'pageheight') ? iecompattest().scrollHeight : iecompattest().offsetHeight;
            for (i = 0; i < no; ++i) {
                yp[i] += sty[i];
                if (yp[i] > doc_height - 50) {
                    xp[i] = Math.random() * (doc_width - am[i] - 30);
                    yp[i] = 0;
                    stx[i] = 0.02 + Math.random() / 10;
                    sty[i] = 0.7 + Math.random()
                }
                dx[i] += stx[i];
                document.getElementById('dot' + i).style.top = yp[i] + 'px';
                document.getElementById('dot' + i).style.left = xp[i] + am[i] * Math.sin(dx[i]) + 'px'
            }
            snowtimer = setTimeout('snowIE_NS6()', 10)
        }

        function hidesnow() {
            if (window.snowtimer) {
                clearTimeout(snowtimer)
            }
            for (i = 0; i < no; i++) document.getElementById('dot' + i).style.visibility = 'hidden'
        }

        if (ie4up || ns6up) {
            snowIE_NS6();
            if (hidesnowtime > 0) setTimeout('hidesnow()', hidesnowtime * 1000)
        }
    </script>
    <?php endif;?>
    <?php
}

function STT_TET_action_links( $links, $file ) {
    if ( strpos( $file, 'devvn-tet-holiday.php' ) !== false ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=setting-tet-holiday' ) . '" title="'.__('Settings').'">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link );
    }
    return $links;
}
add_filter( 'plugin_action_links_' . STT_TET_BASENAME, 'STT_TET_action_links', 10, 2 );


add_action( 'admin_init', 'STT_TET_register_mysettings' );
function STT_TET_register_mysettings() {
    register_setting( 'tet-options-group','tet_options' );
}

add_action( 'admin_menu', 'STT_TET_admin_menu' );
function STT_TET_admin_menu() {
    add_options_page(
        __('Trang trí Tết','devvn-tet-holiday'),
        __('Trang trí Tết','devvn-tet-holiday'),
        'manage_options',
        'setting-tet-holiday',
        'STT_TET_settings_page'
    );
}

function STT_TET_settings_page(){
    $style = esc_attr(devvn_get_tet_holiday_options('style'));
    $bottom_style = esc_attr(devvn_get_tet_holiday_options('bottom_style'));
    $enable_firework = intval(devvn_get_tet_holiday_options('enable_firework'));
    $firework_speed = intval(devvn_get_tet_holiday_options('firework_speed'));
    $firework_number = intval(devvn_get_tet_holiday_options('firework_number'));
    $enable_hoamaidao = esc_attr(devvn_get_tet_holiday_options('enable_hoamaidao'));
    $number_tet_mobile = intval(devvn_get_tet_holiday_options('number_tet_mobile'));
    $number_tet_pc = intval(devvn_get_tet_holiday_options('number_tet_pc'));
    $enable_audio = intval(devvn_get_tet_holiday_options('enable_audio'));
    wp_enqueue_media();
    $left_banner = intval(devvn_get_tet_holiday_options('left_banner'));
    $right_banner = intval(devvn_get_tet_holiday_options('right_banner'));
    ?>
    <style>
        .tet_style_radio input {
            border: 0;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }
        .tet_style_radio img {
            box-shadow: 0 0 0 3px #ddd;
        }
        .tet_style_radio input:checked ~ img {
            box-shadow: 0 0 0 3px #cb0000;
        }
        .tet_style_radio label {
            float: left;
            margin: 0 20px 10px 0;
            cursor: pointer;
        }
        .tet_style_radio:after {
            content: "";
            display: table;
            clear: both;
        }
        .tet_style_radio_bottom img {
            height: 80px;
            width: auto;
        }
        .tet_style_radio label span {
            height: 80px;
            display: block;
            width: 80px;
            text-align: center;
            line-height: 80px;
            box-shadow: 0 0 0 3px #ddd;
        }
        .tet_style_radio_banner label span {
            height: 200px;
            line-height: 200px;
        }
        .tet_style_radio input:checked ~ span {
            box-shadow: 0 0 0 3px #cb0000;
        }
        tr.tet_border_top {
            border-top: 1px solid #ddd;
        }
        .tet-upload-image {
            width: 100%;
            max-width: 125px;
        }
        .tet-upload-image img{
            max-width: 100%;
            height: auto;
        }
        .view-has-value {
            display: none;
            position: relative;
        }

        .has-image .view-has-value {
            display: inline-block;
        }

        .hidden-has-value {
            display: block;
        }

        .has-image .hidden-has-value {
            display: none;
        }

        a.svl-delete-image {
            position: absolute;
            top: 0;
            right: -25px;
            color: #fff;
            background: #000;
            display: block;
            width: 20px;
            height: 20px;
            text-align: center;
            text-decoration: none;
        }
        .tet_hide{
            display: none !important;
        }
    </style>
    <div class="wrap">
        <h1><?php _e('Trang trí Tết', 'devvn-tet-holiday');?></h1>
        <p>
            <strong style="color: red; font-size: 20px; font-style: italic">Chúc mừng năm mới!</strong>
        </p>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'tet-options-group' );?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Chọn kiểu', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <div class="tet_style_radio tet_style_radio_banner">
                            <label>
                                <input type="radio" name="tet_options[style]" value="style_1" <?php checked('style_1', $style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/style-1.jpg';?>" alt="">
                            </label>
                            <label>
                                <input type="radio" name="tet_options[style]" value="style_2" <?php checked('style_2', $style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/style-2.jpg';?>" alt="">
                            </label>
                            <label>
                                <input type="radio" name="tet_options[style]" value="style_3" <?php checked('style_3', $style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/style-3.png';?>" alt="">
                            </label>
                            <label>
                                <input type="radio" name="tet_options[style]" value="style_4" <?php checked('style_4', $style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/style-4.jpg';?>" alt="">
                            </label>
                            <label>
                                <input type="radio" name="tet_options[style]" value="custom" <?php checked('custom', $style, true);?>>
                                <span>Tuỳ chỉnh</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="tet_style_custom <?php echo ($style == 'custom') ? '' : 'tet_hide';?>">
                    <th scope="row"><label><?php _e('Hình ảnh 2 bên', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <div class="tet-upload-image <?php if($left_banner):?>has-image<?php endif;?>">
                                        <div class="view-has-value">
                                            <input type="hidden" class="clone_delete" name="tet_options[left_banner]" id="maps_marker_icon" value="<?php echo $left_banner;?>"/>
                                            <img src="<?php echo wp_get_attachment_image_url($left_banner,'full')?>" class="image_view pins_img"/>
                                            <a href="#" class="svl-delete-image">x</a>
                                        </div>
                                        <div class="hidden-has-value"><input type="button" class="tet-upload button" value="<?php _e( 'Chọn ảnh bên trái', 'devvn-tet-holiday' )?>" /></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="tet-upload-image <?php if($right_banner):?>has-image<?php endif;?>">
                                        <div class="view-has-value">
                                            <input type="hidden" class="clone_delete" name="tet_options[right_banner]" id="maps_marker_icon" value="<?php echo $right_banner;?>"/>
                                            <img src="<?php echo wp_get_attachment_image_url($right_banner,'full')?>" class="image_view pins_img"/>
                                            <a href="#" class="svl-delete-image">x</a>
                                        </div>
                                        <div class="hidden-has-value"><input type="button" class="tet-upload button" value="<?php _e( 'Chọn ảnh bên phải', 'devvn-tet-holiday' )?>" /></div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Chọn kiểu chân trang', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <div class="tet_style_radio tet_style_radio_bottom">
                            <label>
                                <input type="radio" name="tet_options[bottom_style]" value="none" <?php checked('none', $bottom_style, true);?>>
                                <span>Ẩn</span>
                            </label>
                            <label>
                                <input type="radio" name="tet_options[bottom_style]" value="bottom_1" <?php checked('bottom_1', $bottom_style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/bottom-1.png';?>" alt="">
                            </label>
                            <label>
                                <input type="radio" name="tet_options[bottom_style]" value="bottom_2" <?php checked('bottom_2', $bottom_style, true);?>>
                                <img src="<?php echo STT_TET_URL . 'images/bottom-2.png';?>" alt="">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr class="tet_border_top">
                    <th scope="row"><label><?php _e('Bật pháo hoa', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <label style="margin-right: 20px;"><input type="radio" value="2" name="tet_options[enable_firework]" <?php checked(2, $enable_firework, true);?>/> Tắt</label>
                        <label><input type="radio" value="1" name="tet_options[enable_firework]" <?php checked(1, $enable_firework, true);?>/> Bật</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Tốc độ bắn pháo hoa', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="1" value="<?php echo $firework_speed;?>" name="tet_options[firework_speed]" />
                        <br><small>Số càng nhỏ thì bắn càng nhanh. Mặc định là 30</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Số pháo hoa bắn cùng lúc', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="1" value="<?php echo $firework_number;?>" name="tet_options[firework_number]" />
                        <br><small>Mặc định là 5</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Âm thanh khi bắn pháo hoa', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <label style="margin-right: 20px;"><input type="radio" value="0" name="tet_options[enable_audio]" <?php checked(0, $enable_audio, true);?>/> Tắt</label>
                        <label><input type="radio" value="1" name="tet_options[enable_audio]" <?php checked(1, $enable_audio, true);?>/> Bật</label>
                        <br><small>Có thể không chạy ở 1 số trình duyệt. Và có thể lần đầu không chạy nhưng khi chuyển trang sẽ chạy</small>
                    </td>
                </tr>
                <tr class="tet_border_top">
                    <th scope="row"><label><?php _e('Bật hoa rơi', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <label style="margin-right: 20px;"><input type="radio" value="none" name="tet_options[enable_hoamaidao]" <?php checked('none', $enable_hoamaidao, true);?>/> Tắt</label>
                        <label style="margin-right: 20px;"><input type="radio" value="dao" name="tet_options[enable_hoamaidao]" <?php checked('dao', $enable_hoamaidao, true);?>/> Hoa Đào <img width="15" src="<?php echo esc_url(STT_TET_imgs('dao', 'url'));?>" alt=""> </label>
                        <label style="margin-right: 20px;"><input type="radio" value="mai" name="tet_options[enable_hoamaidao]" <?php checked('mai', $enable_hoamaidao, true);?>/> Hoa Mai <img width="15" src="<?php echo esc_url(STT_TET_imgs('mai', 'url'));?>" alt=""></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Số hoa trên PC', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="1" value="<?php echo $number_tet_pc;?>" name="tet_options[number_tet_pc]" />
                        <br><small>Mặc định là 20</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Số hoa trên Mobile', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="1" value="<?php echo $number_tet_mobile;?>" name="tet_options[number_tet_mobile]" />
                        <br><small>Mặc định là 10</small>
                    </td>
                </tr>
                <tr class="tet_border_top">
                    <th scope="row"><label><?php _e('Container Width', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="0" value="<?php echo intval(devvn_get_tet_holiday_options('container_width'));?>" name="tet_options[container_width]" />
                        <br><small>Chiều rộng website của bạn. Khi màn hình vượt qua số này + chiều rộng của mỗi kiểu thì sẽ ẩn các ảnh trang trí Tết đi</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Z-index', 'devvn-tet-holiday')?></label></th>
                    <td>
                        <input type="number" min="0" value="<?php echo intval(devvn_get_tet_holiday_options('zindex'));?>" name="tet_options[zindex]" />
                    </td>
                </tr>
                <?php do_settings_fields('tet-options-group', 'default'); ?>
                </tbody>
            </table>
            <?php do_settings_sections('tet-options-group', 'default'); ?>

            <?php submit_button();?>
        </form>
    </div>
    <script type="text/javascript">
        (function ($){
            $(document).ready(function (){
               $('input[name="tet_options[style]"]').on('change', function (){
                   let thisVal = $('input[name="tet_options[style]"]:checked').val();
                   if(thisVal == 'custom'){
                       $('.tet_style_custom').removeClass('tet_hide');
                   }else{
                       $('.tet_style_custom').addClass('tet_hide');
                   }
               });
               
                //image upload
                $('body').on('click','.tet-upload',function(e){
                    // Prevents the default action from occuring.
                    e.preventDefault();
                    var thisUpload = $(this).parents('.tet-upload-image');
                    // Sets up the media library frame
                    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                        title: 'Upload Image',
                        button: { text:  'Upload Image' },
                        library: { type: 'image' },
                        multiple: false
                    });
                    // Runs when an image is selected.
                    meta_image_frame.on('select', function(){
                        // Grabs the attachment selection and creates a JSON representation of the model.
                        var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
                        // Sends the attachment URL to our custom image input field.

                        if ( media_attachment.id ) {
                            var attachment_image = media_attachment.sizes && media_attachment.sizes.thumbnail ? media_attachment.sizes.thumbnail.url : media_attachment.url;

                            thisUpload.addClass('has-image');
                            thisUpload.find('input[type="hidden"]').val(media_attachment.id);
                            thisUpload.find('img.image_view').attr('src',media_attachment.url);
                        }
                    });
                    // Opens the media library frame.
                    meta_image_frame.open();
                });


                $('body').on('click','.svl-delete-image',function(){
                    var parentDiv = $(this).parents('.tet-upload-image');
                    parentDiv.removeClass('has-image');
                    parentDiv.find('input[type="hidden"]').val('');
                    return false;
                });
            });
        })(jQuery);
    </script>
    <?php
}


function devvn_get_tet_holiday_options($name = ''){
    $options = wp_parse_args(get_option('tet_options'),array(
        'zindex' => 99,
        'style' => 'style_1',
        'left_banner' => '',
        'right_banner' => '',
        'container_width' => 1140,
        'show_mobile' => 0,
        'bottom_style' => 'bottom_2',
        'enable_firework' => 1,
        'firework_speed' => 30,
        'firework_number' => 5,
        'enable_hoamaidao' => 'dao',
        'number_tet_mobile' => 10,
        'number_tet_pc' => 20,
        'enable_audio' => 0,
    ));
    if($name){
        return (isset($options[$name]) && $options[$name]) ? $options[$name] : '';
    }
    return $options;
}