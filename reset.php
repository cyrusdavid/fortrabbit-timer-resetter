<?php

require 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

$client = new Client();
$client->getClient()->setConfig(array('debug' => true));
$crawler = $client->request('GET', 'https://my.fortrabbit.com/login/');

$form = $crawler->selectButton('Login')->form();
$form['data[Account][email]'] = getenv('FRB_USERNAME');
$form['data[Account][password]'] = getenv('FRB_PASSWORD');

$crawler = $client->submit($form);

$links = $crawler->filter('.listapp h1 a');

if ( $links->count())
{
    echo "No apps found.\n";
    exit(1);
}

$links->each(function($link) use ($client)
{
    echo 'Resetting timer for app ' . $link->text() . '.';
    $crawler = $client->click($link->link());
    echo '.';
    $form = $crawler->selectButton('Reset Timer')->form();
    echo '.';

    $client->submit($form);
    echo " Done\n";
});

