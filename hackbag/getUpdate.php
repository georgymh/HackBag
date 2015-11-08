<?php
	include '../parse.php';
	use Parse\ParseUser;
	use Parse\ParseQuery;

	include 'funcs.php';

	$user = ParseUser::getCurrentUser();
	$trans = $user->get('currentTransaction');
	$trans->fetch();

	echo getUserType($user);
?>