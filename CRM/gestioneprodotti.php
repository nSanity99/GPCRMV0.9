<?php
session_start();
require_once 'db_config.php';

// Solo admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || ($_SESSION['ruolo'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

$success_message = $_SESSION['catalogo_success'] ?? '';
$error_message = $_SESSION['catalogo_error'] ?? '';
unset($_SESSION['catalogo_success'], $_SESSION['catalogo_error']);

$conn = isset($db_port)
    ? new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port)
    : new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_prodotto'] ?? '');
    if ($nome === '') {
        $_SESSION['catalogo_error'] = "Nome prodotto mancante.";
    } elseif ($conn->connect_error) {
        $_SESSION['catalogo_error'] = "Errore connessione database.";
    } else {
        $stmt = $conn->prepare("INSERT INTO catalogo_prodotti (nome) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param('s', $nome);
            if ($stmt->execute()) {
                $_SESSION['catalogo_success'] = "Prodotto aggiunto con successo.";
            } else {
                $_SESSION['catalogo_error'] = $conn->errno == 1062
                    ? "Prodotto già esistente."
                    : "Errore durante l'inserimento.";
            }
            $stmt->close();
        } else {
            $_SESSION['catalogo_error'] = "Errore preparazione statement.";
        }
    }
    $conn->close();
    header("Location: gestioneprodotti.php");
    exit;
}

$prodotti = [];
if (!$conn->connect_error) {
    $res = $conn->query("SELECT id, nome FROM catalogo_prodotti ORDER BY nome ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $prodotti[] = $row;
        }
        $res->free();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Prodotti</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background-color: #f8f9fa; color: #495057; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; }
        .page-outer-container { max-width: 900px; margin: 25px auto; padding: 0; animation: fadeInSlideUp 0.6s ease-out forwards; }
        .module-header { display: flex; justify-content: space-between; align-items: center; background-color: #ffffff; padding: 18px 30px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); border-bottom: 4px solid #B08D57; margin-bottom: 30px; }
        .header-branding { display: flex; align-items: center; }
        .header-branding .logo { max-height: 45px; margin-right: 15px; }
        .header-titles h1 { font-size: 1.5em; color: #2E572E; margin: 0; font-weight: 600; }
        .header-titles h2 { font-size: 0.9em; color: #6c757d; margin: 0; font-weight: 400; }
        .user-session-controls { display: flex; align-items: center; gap: 15px; }
        .user-session-controls .nav-link-button, .user-session-controls .logout-button { padding: 7px 14px; font-size: 0.9em; color: white !important; text-decoration: none; border-radius: 5px; transition: background-color 0.2s ease, transform 0.2s ease; font-weight: 500; }
        .user-session-controls .nav-link-button { background-color: #6c757d; }
        .user-session-controls .nav-link-button:hover { background-color: #5a6268; transform: translateY(-1px); }
        .user-session-controls .logout-button { background-color: #D42A2A; }
        .user-session-controls .logout-button:hover { background-color: #c82333; transform: translateY(-1px); }
        .module-content { background-color: #ffffff; padding: 25px 30px; border-radius: 8px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
        table.catalogo-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.95em; }
        .catalogo-table th, .catalogo-table td { border: 1px solid #dee2e6; padding: 12px 15px; text-align: left; }
        .catalogo-table th { background-color: #f8f9fa; font-weight: 600; }
        .feedback-message { margin-bottom: 20px; padding: 12px 15px; border-radius: 5px; font-weight: 500; text-align: center; }
        .feedback-message.success { color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; }
        .feedback-message.error { color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; }
    </style>
</head>
<body>
    <div class="page-outer-container">
        <header class="module-header">
            <div class="header-branding">
                <a href="dashboard.php"><img src="logo.png" alt="Logo Gruppo Vitolo" class="logo"></a>
                <div class="header-titles">
                    <h1>Gestione Prodotti</h1>
                    <h2>Catalogo</h2>
                </div>
            </div>
            <div class="user-session-controls">
                <a href="dashboard.php" class="nav-link-button">Dashboard</a>
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </header>
        <main class="module-content">
            <?php if ($success_message): ?>
                <div class="feedback-message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php elseif ($error_message): ?>
                <div class="feedback-message error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST" action="gestioneprodotti.php">
                <div class="form-group">
                    <label for="nome_prodotto">Nuovo prodotto:</label>
                    <input type="text" id="nome_prodotto" name="nome_prodotto" required>
                </div>
                <button type="submit" class="admin-button">Aggiungi Prodotto</button>
            </form>
            <table class="catalogo-table">
                <thead>
                    <tr><th>ID</th><th>Nome</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($prodotti as $p): ?>
                        <tr><td><?php echo $p['id']; ?></td><td><?php echo htmlspecialchars($p['nome']); ?></td></tr>
                    <?php endforeach; ?>
                    <?php if (empty($prodotti)): ?>
                        <tr><td colspan="2" style="text-align:center;">Nessun prodotto presente.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
        <footer class="footer-logo-area">
            <img src="logo.png" alt="Logo Gruppo Vitolo">
        </footer>
    </div>
</body>
</html>
