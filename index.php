<?php
require_once __DIR__.'/config/config.php';

$page=$_GET['page']??'home';

$pages=[
    'home',
    'login',
    'cadastro',
    'logout',
    'demandas',
    'demanda',
    'painel',
    'nova-demanda',
    'editar-demanda',
    'participacoes',
    'perfil',
    'interessados',
    'notificacoes'
];

if(!in_array($page,$pages,true)){
    http_response_code(404);
    exit('Página não encontrada.');
}

if($page==='logout'){
    logout_user();
    redirect_to(url());
}

require __DIR__.'/views/'.$page.'.php';

