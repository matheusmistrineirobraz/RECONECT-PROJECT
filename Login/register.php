<?php
session_start();

$message = '';
$messageType = 'danger';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['name'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirmPassword = $_POST['confirm_password'] ?? '';

	if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = 'Preencha o nome e informe um e-mail válido.';
	} elseif (strlen($password) < 6) {
		$message = 'A senha deve ter pelo menos 6 caracteres.';
	} elseif ($password !== $confirmPassword) {
		$message = 'As senhas não coincidem.';
	} else {
		try {
			$hash = password_hash($password, PASSWORD_DEFAULT);

			if (isset($pdo) && $pdo instanceof PDO) {
				$check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
				$check->execute([$email]);
				if ($check->fetch()) {
					throw new RuntimeException('Este e-mail já está cadastrado.');
				}
				$stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
				$stmt->execute([$name, $email, $hash]);
			} elseif (isset($conn) && $conn instanceof mysqli) {
				$check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
				$check->bind_param('s', $email);
				$check->execute();
				if ($check->get_result()->fetch_assoc()) {
					throw new RuntimeException('Este e-mail já está cadastrado.');
				}
				$stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
				$stmt->bind_param('sss', $name, $email, $hash);
				$stmt->execute();
			} else {
				throw new RuntimeException('Conexão com o banco de dados não encontrada.');
			}

			$message = 'Cadastro realizado com sucesso! Você já pode entrar.';
			$messageType = 'success';
		} catch (Throwable $error) {
			$message = $error->getMessage();
		}
	}
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Criar conta | RECONECT</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
	<main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
		<section class="card shadow-sm border-0" style="max-width: 480px; width: 100%;">
			<div class="card-body p-4 p-md-5">
				<h1 class="h3 text-center mb-2">Criar conta</h1>
				<p class="text-center text-muted mb-4">Junte-se à RECONECT</p>

				<?php if ($message !== ''): ?>
					<div class="alert alert-<?= htmlspecialchars($messageType) ?>" role="alert">
						<?= htmlspecialchars($message) ?>
					</div>
				<?php endif; ?>

				<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
					<div class="mb-3">
						<label for="name" class="form-label">Nome</label>
						<input type="text" class="form-control" id="name" name="name" required maxlength="120" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">E-mail</label>
						<input type="email" class="form-control" id="email" name="email" required maxlength="190" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
					</div>
					<div class="mb-3">
						<label for="password" class="form-label">Senha</label>
						<input type="password" class="form-control" id="password" name="password" minlength="6" required>
					</div>
					<div class="mb-4">
						<label for="confirm_password" class="form-label">Confirmar senha</label>
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
					</div>
					<button type="submit" class="btn btn-primary w-100" >Cadastrar</button>
				</form>
				<p class="text-center mt-4 mb-0">Já tem uma conta? <a href="Login.php">Entrar</a></p>
			</div>
		</section>
	</main>
</body>
</html>
