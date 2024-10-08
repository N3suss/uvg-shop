<?php
session_start();
include 'db.php'; 


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];


$sql = $role === 'admin' ? "SELECT * FROM pedidos" : "SELECT * FROM pedidos WHERE cliente_username = '$username'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - UVG-Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-white p-6 shadow-md text-center">
        <h1 class="text-3xl font-bold">Bienvenido, <?= htmlspecialchars($username) ?></h1>
    </header>

    <main class="container mx-auto py-10 text-center">
        <h2 class="text-xl font-semibold mb-6"><?= $role === 'cliente' ? 'Mis Pedidos 📦' : 'Gestión de Pedidos 📦' ?></h2>

        <?php if ($role === 'cliente' && isset($_SESSION['pedido_generado'])): ?>
            <p class="text-green-500 mb-4">¡Pedido generado exitosamente!</p>
            <?php unset($_SESSION['pedido_generado']); ?>
        <?php endif; ?>

        <?php if ($role === 'cliente'): ?>
            <form action="generar_pedido.php" method="POST" class="mb-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Generar Pedido</button>
            </form>
        <?php endif; ?>

        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2">ID</th>
                    <?php if ($role === 'admin') echo '<th class="py-2">Cliente</th>'; ?>
                    <th class="py-2">Estado</th>
                    <th class="py-2">Fecha</th>
                    <?php if ($role === 'admin') echo '<th class="py-2">Acciones</th>'; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 border-b"><?= $pedido['id'] ?></td>
                        <?php if ($role === 'admin'): ?>
                            <td class="py-2 border-b"><?= $pedido['cliente_username'] ?></td>
                        <?php endif; ?>
                        <td class="py-2 border-b"><?= $pedido['estado'] ?></td>
                        <td class="py-2 border-b"><?= $pedido['fecha'] ?></td>
                        <?php if ($role === 'admin'): ?>
                            <td class="py-2 border-b">
                                <?php if ($pedido['estado'] !== 'Listo'): ?>
                                    <form action="actualizar_pedido.php" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                        <select name="estado">
                                            <?php foreach (['En Proceso', 'Listo'] as $opcion): ?>
                                                <?php if ($opcion !== $pedido['estado']): ?>
                                                    <option value="<?= $opcion ?>"><?= $opcion ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Actualizar</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-500">Concluido✅</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        
        
        <div class="mt-10">
            <a href="logout.php" class="text-blue-500">Cerrar Sesión</a>
        </div>
    </main>
</body>
</html>
