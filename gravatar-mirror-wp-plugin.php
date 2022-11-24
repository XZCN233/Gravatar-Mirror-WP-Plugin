<?php
/**
 * Plugin Name: Gravatar 镜像源加速器
 * Plugin URI:  https://xiaozhao233.top/mirror-wp-plugin/#Gravatar
 * Description: Gravatar 头像在中国大陆地区无法稳定访问，解决办法就是使用可用的 Gravatar 镜像源替换无法访问的官方源，本插件可针对中国大陆地区网站对 Gravatar 头像服务进行替换加速。我们收录了多个可用镜像源供您选择。当然，您也可以使用自己搭建的镜像源。
 * Version: 1.0
 * Author: 中国的小赵
 * Author URI: https://xiaozhao233.top/
 */

//主体模块
//判断是否为后台，调用show_menu函数
if (is_admin()){
    add_action('admin_menu','show_menu');
}

//注册插件设置页
function show_menu(){
    add_options_page('Gravatar 镜像源加速器设置','Gravatar 镜像源',1,__FILE__, 'gravatar_setting_menu');
}

//插件设置页
function gravatar_setting_menu() {
?>
    <div class="header_title">
        <h1>Gravatar 镜像源加速器插件 | ⚙设置</h1>
    </div>
    <div class="gravatar_mirror_domain_setting">
        <h2>镜像源地址设置</h2>
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options');//这行代码用来保存表单中内容到数据库?>
            <input type="text" name="gravatar_mirror_domain" placeholder="Gravatar 镜像源地址" value="<?php echo get_option('gravatar_mirror_domain');?>"> 
            <input type="hidden" name="action" value="update"/>
            <input type="hidden" name="page_options" value="gravatar_mirror_domain"/>
            <input type="submit" value="保存" class="button-primary"/>
            <h3><strong>注意：</strong>请仅输入域名，本人技术不行，多一个 "/" 都会出错</h3>
        </form>
    </div>
    <br/>
    <div class="mirror-list">
        <h2>可用镜像源列表，请自行复制进上方输入框</h2>
        <?php
            //从XiaoZhao服务器获取最新的镜像列表供选择
            echo file_get_contents("https://xiaozhao233.top/mirror-wp-plugin/api/mirror-list/index.php?type=gravatar&date=".date("Ymd"));
        ?>
    </div>
    <div class="footer_copyright_info">
        <h4>Copyright©2022. <strong><a href="https://xiaozhao233.top/">中国的小赵</a></strong>. All Right Reserved.</h4>
        <a href="https://afdian.net/a/xiaozhao233"><img src="https://s1.slb.icu/contact/aifadian/button-purple.png" height="100px"/></a>
    </div>
<?php  
}  

//Gravatar 头像加速模块
function Gravatar($avatar) {
	$avatar = str_replace(array("0.gravatar.com","1.gravatar.com","www.gravatar.com","2.gravatar.com","secure.gravatar.com","s.gravatar.com","cn.gravatar.com"),get_option('gravatar_mirror_domain'),$avatar);
	return $avatar;
}
function Gravatar_start() {
	ob_start("Gravatar");
}
function Gravatar_end() {
	ob_end_flush();
}
add_action('init', 'Gravatar_start');
add_action('shutdown', 'Gravatar_end');
