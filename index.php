<?php

include('sitemap.php');

$host = 'site.ru';
$paths = 'C:/web/www/'.$host.'/upload/';
$data = array(
    array("https://site.ru/", "https://site.ru/news", "https://site.ru/about", "https://site.ru/products", "https://site.ru/products/ps5", "https://site.ru/products/xbox", "https://site.ru/products/wii"), #Страницы сайта
    array("2020-12-14", "2020-12-10", "2020-12-07", "2020-12-12", "2020-12-11", "2020-12-12", "2020-12-11"), #Дата последнего обновления
    array("1", "0.5", "0.1", "0.5", "0.1", "0.1", "0.1"), #Приоритет обновления
    array("hourly", "daily", "weekly", "daily", "weekly", "weekly", "weekly"), #Частота обновления
);
$ext = 'xml';

$sitemap = new Sitemap();
$Sitemap->create_sitemap($data, 'xml', $paths);
$Sitemap->create_sitemap($data, 'csv', $paths);
$Sitemap->create_sitemap($data, 'json', $paths);
?>