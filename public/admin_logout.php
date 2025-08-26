<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
	echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="3;url=index.html"><title>Access Denied</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container mt-5"><div class="alert alert-danger"><h4>Access Denied</h4><p>This page is restricted. Please log in as admin to access this function.</p><p>You will be redirected to the home page.</p></div></div></body></html>';
	exit();
}
session_destroy();
header('Location: index.html');
exit();
?>
