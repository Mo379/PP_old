<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointview = new MVC\pointview;
$questionview = new MVC\questionview;



//setup 
$raw_info = $pointview -> make_sitemap();
$qs_chapter = $pointview -> make_sitemap_questions_chapter();
$qs_topic = $pointview -> make_sitemap_questions_topic();
$past_papers = $questionview -> make_sitemap_past_papers();
//output
echo $twig->render('sitemap.t.xml',['raw_info_spec' => $raw_info,'qs_chapter' => $qs_chapter,'qs_topic' => $qs_topic,'past_papers'=>$past_papers]);
?>
