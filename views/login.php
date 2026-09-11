<?php
if(current_user()) redirect_to(url('painel'));
if(is_post()){check_csrf();$email=trim($_POST['email']??'');$senha=$_POST['senha']??'';
$s=db()->prepare("SELECT * FROM usuarios WHERE email=? AND status='ativo' LIMIT 1");$s->execute([$email]);$u=$s->fetch();
if($u&&password_verify($senha,$u['senha'])){login_user($u);redirect_to(url('painel'));} flash('error','E-mail ou senha inválidos.');redirect_to(url('login'));}
$title='Entrar - '.APP_NAME;require __DIR__.'/../includes/header.php';?>
<div class="form"><h1>Entrar</h1><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>">
<label>E-mail<input type="email" name="email" required></label><label>Senha<input type="password" name="senha" required></label>
<button class="btn">Entrar</button></form><p>Não possui conta? <a href="<?=url('cadastro')?>">Cadastre-se</a></p></div>
<?php require __DIR__.'/../includes/footer.php'; ?>