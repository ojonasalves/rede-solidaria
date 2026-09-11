<?php
if(current_user()) redirect_to(url('painel'));
if(is_post()){check_csrf();$nome=trim($_POST['nome']??'');$email=trim($_POST['email']??'');$senha=$_POST['senha']??'';$tipo=$_POST['tipo']??'';
if($nome===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($senha)<6||!in_array($tipo,['ong','solidario'],true)){flash('error','Preencha corretamente. Senha mínima: 6 caracteres.');redirect_to(url('cadastro'));}
$c=db()->prepare("SELECT id FROM usuarios WHERE email=?");$c->execute([$email]);if($c->fetch()){flash('error','E-mail já cadastrado.');redirect_to(url('cadastro'));}
$pdo=db();$pdo->beginTransaction();try{$s=$pdo->prepare("INSERT INTO usuarios(nome,email,senha,tipo,status) VALUES(?,?,? ,?,'ativo')");$s->execute([$nome,$email,password_hash($senha,PASSWORD_DEFAULT),$tipo]);$id=(int)$pdo->lastInsertId();
if($tipo==='ong')$pdo->prepare("INSERT INTO ongs(usuario_id,nome_fantasia) VALUES(?,?)")->execute([$id,$nome]);else $pdo->prepare("INSERT INTO solidarios(usuario_id) VALUES(?)")->execute([$id]);
$pdo->commit();flash('success','Cadastro realizado. Faça login.');redirect_to(url('login'));}catch(Throwable $e){$pdo->rollBack();flash('error','Erro ao criar cadastro.');redirect_to(url('cadastro'));}}
$title='Cadastro - '.APP_NAME;require __DIR__.'/../includes/header.php';?>
<div class="form"><h1>Criar cadastro</h1><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>">
<label>Nome<input name="nome" required></label><label>E-mail<input type="email" name="email" required></label>
<label>Senha<input type="password" name="senha" minlength="6" required></label><label>Tipo<select name="tipo" required><option value="">Selecione</option><option value="ong">ONG</option><option value="solidario">Solidário</option></select></label>
<button class="btn">Criar conta</button></form></div><?php require __DIR__.'/../includes/footer.php'; ?>