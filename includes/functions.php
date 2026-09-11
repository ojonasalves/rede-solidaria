<?php
declare(strict_types=1);

function e(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function url(string $page='home', array $params=[]): string {
return '/index.php?' . http_build_query(array_merge(['page'=>$page], $params));
}

function redirect_to(string $u): never { header('Location: '.$u); exit; }
function is_post(): bool { return $_SERVER['REQUEST_METHOD']==='POST'; }
function csrf(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function check_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Token de segurança inválido.');
    }
}
function flash(string $type,string $msg): void { $_SESSION['flash']=[$type,$msg]; }
function flash_get(): ?array { $x=$_SESSION['flash']??null; unset($_SESSION['flash']); return $x; }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function login_user(array $u): void { unset($u['senha']); session_regenerate_id(true); $_SESSION['user']=$u; }
function require_login(): void { if (!current_user()) redirect_to(url('login')); }
function require_role(string $role): void {
    require_login();
    if ((current_user()['tipo'] ?? '') !== $role) { http_response_code(403); exit('Acesso negado.'); }
}
function require_admin(): void {
    require_login();
    if ((current_user()['tipo'] ?? '') !== 'admin') { http_response_code(403); exit('Acesso negado.'); }
}
function logout_user(): void { $_SESSION=[]; if (ini_get('session.use_cookies')) {
    $p=session_get_cookie_params(); setcookie(session_name(),'',['expires'=>time()-42000,'path'=>$p['path'],'domain'=>$p['domain'],'secure'=>$p['secure'],'httponly'=>$p['httponly'],'samesite'=>'Lax']);
} session_destroy(); }
