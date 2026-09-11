<?php
declare(strict_types=1);
session_start();

const APP_NAME = 'Rede Solidária';
const DB_HOST = 'localhost';
const DB_NAME = 'jonasa20_rede_solidaria';
const DB_USER = 'jonasa20_rede_admin';
const DB_PASS = '@Jonas123e4@';

date_default_timezone_set('America/Sao_Paulo');
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
