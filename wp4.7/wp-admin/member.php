<?php
/**
 * About This Version administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( current_user_can( 'customize' ) ) {
	wp_enqueue_script( 'customize-loader' );
}

global $wpdb;
// $helloworld_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = '12'");
// echo $helloworld_id;
// die();



// $wpdb->insert('wp_users', array('id'=>1, 'user_login'=>'admin1'));



$row = $wpdb->get_results("SELECT wp_users FROM member_clb");

if(empty($row)){
   $wpdb->query("ALTER TABLE wp_users ADD member_clb INT(1) NOT NULL DEFAULT 1");
}

// $servername = "localhost";
// $username = "wp";
// $password = "123456";
// $dbname = "wp";
// $conn = mysqli_connect($servername, $username, $password, $dbname);
//
// $wpdb->query($wpdb->prepare("UPDATE wp_posts SET user_login='admin1' WHERE id=1"));
//
// var_dump($wpdb);

$email = $_GET['email'];
$id_user = get_current_user_id();
$userData = get_userdata( $id_user );

$userDataMember = (int)$userData->member_clb;

// $_member_of_clb = $userData->_member_of_clb;

$_member_of_clb = get_user_meta($id_user, '_member_of_clb', true);


$emailUser = $userData->user_email;

$noti1= '';
$noti12 = '';

var_dump($userDataMember);
die();
if($email === $emailUser && $userDataMember === 0 && $_member_of_clb === "2"){
	// $sql = "UPDATE wp_users SET user_member=1 WHERE id=$id_user";
  // $update = wp_update_user( array(
  //        'id' => $id_user,
  //        'member_clb' => 2
  //   ) );
  $update = update_user_meta( $id_user, '_member_of_clb','1' );
  $noti1 = 'Chào mừng bạn đến với CLB Cán bộ trẻ thành phố Đà Nẵng';
  $noti2 = 'Cảm ơn bạn đã đăng ký thành công! Bây giờ bạn có thể vào trang <a class="navbar-brand" href="http://gg-canbotre.com/">CLB cán bộ trẻ Đà Nẵng</a> và diễn đàn giao lưu cùng mọi người.';

	// if ($update===1) {
	// 	$noti1 = 'Chào mừng bạn đến với CLB Cán bộ trẻ thành phố Đà Nẵng';
	// 	$noti2 = 'Cảm ơn bạn đã đăng ký thành công! Bây giờ bạn có thể vào trang <a class="navbar-brand" href="http://gg-canbotre.com/">CLB cán bộ trẻ Đà Nẵng</a> và diễn đàn giao lưu cùng mọi người.';
	// } else {
	// 	$noti1 = 'Lỗi Database!';
	// 	$noti2 = 'Vui lòng liên hệ Admin để xử lý.';
	// }
}
else if($email === $emailUser && $userDataMember === 1 && $_member_of_clb === "1"){
	$noti1 = 'Bạn đang là thành viên của CLB Cán Bộ Trẻ.';
	$noti2 = 'Bạn có thể vào trang <a class="navbar-brand" href="http://gg-canbotre.com/">CLB cán bộ trẻ Đà Nẵng</a> và diễn đàn giao lưu cùng mọi người.';
}
else if($email === $emailUser && $userDataMember === 1 && $_member_of_clb === "2"){
	$noti1 = 'Bạn đã bị xóa quyền thành viên trong CLB hãy liên hệ Admin để xử lý.';
	$noti2 = 'Vui lòng kiểm tra lại tài khoản của bạn.';
}
else if($email !== $emailUser){
	$noti1 = 'Bạn đang đăng nhập với một tài khoản khác!';
	$noti2 = 'Vui lòng kiểm tra lại tài khoản của bạn.';
}
else{
	$noti1 = 'Bạn chưa được xác nhận vào thành viên CLB';
	$noti2 = 'Vui lòng liên hệ Admin để xử lý!';
}

include( ABSPATH . 'wp-admin/admin-header.php' );
?>
	<div class="wrap about-wrap">
		<h1><?php printf( __( $noti1 )); ?></h1>

		<div class="about-text"><?php printf( __( $noti2 )); ?></div>

	</div>
<?php

// include( ABSPATH . 'wp-admin/admin-footer.php' );

// These are strings we may use to describe maintenance/security releases, where we aim for no new strings.
return;
