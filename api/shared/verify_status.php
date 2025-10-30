<?php
session_cache_limiter();
session_start();

$user_id = $_SESSION["user_id"];

if (empty($user_id)) {
  header("Location: ../../index.html");
  exit;
}