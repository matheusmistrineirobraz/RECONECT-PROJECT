<?php
session_start();

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = trim($_POST['email'] ?? '');
	$senha = $_POST['senha'] ?? '';

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$erro = 'Informe um e-mail válido.';
	} elseif ($senha === '') {
		$erro = 'Informe sua senha.';
	} else {
		// Substitua este bloco pela validação do usuário no banco de dados.
		$_SESSION['usuario_email'] = $email;
		header('Location: ../app/Index.php');
		exit;
	}
}

$sucesso = isset($_GET['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | RECONECT</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			min-height: 100vh;
			background: linear-gradient(135deg, #0d6efd, #6f42c1);
		}
		.login-card {
			max-width: 430px;
			border: 0;
			border-radius: 1rem;
		}
		.brand {
			color: #0d6efd;
			font-weight: 700;
			letter-spacing: .08rem;
		}
	</style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
	<main class="card login-card shadow-lg w-100">
		<div class="card-body p-4 p-md-5">
			<div class="text-center mb-4">
				<h1 class="brand h3 mb-2">RECONECT</h1>
				<p class="text-body-secondary mb-0">Acesse sua conta</p>
			</div>

			<?php if ($erro !== ''): ?>
				<div class="alert alert-danger" role="alert">
					<?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
				</div>
			<?php elseif ($sucesso): ?>
				<div class="alert alert-success" role="alert">
					Login realizado com sucesso!
				</div>
			<?php endif; ?>

			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" novalidate>
				<div class="mb-3">
					<label for="email" class="form-label">E-mail</label>
					<input type="email" class="form-control form-control-lg" id="email" name="email"
						   value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
						   placeholder="seu@email.com" autocomplete="email" required>
				</div>

				<div class="mb-3">
					<label for="senha" class="form-label">Senha</label>
					<input type="password" class="form-control form-control-lg" id="senha" name="senha"
						   placeholder="Digite sua senha" autocomplete="current-password" required>
				</div>

				<div class="d-flex justify-content-between align-items-center mb-4">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
						<label class="form-check-label" for="lembrar">Lembrar-me</label>
					</div>
					<a href="forgot-password.php" class="text-decoration-none">Esqueci a senha</a>
				</div>

				<button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
			</form>

			<p class="text-center text-body-secondary mt-4 mb-0">
				Ainda não possui uma conta?
				<a href="Register.php" class="text-decoration-none">Cadastre-se</a>
			</p>
		</div>
	</main>
</body>
</html>
