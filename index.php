<?php
include "conexion.php";

$LOW_STOCK_THRESHOLD = 5;

$resTotalProducts = $conn->query("SELECT COUNT(*) AS total FROM productos");
$totalProducts = ($row = $resTotalProducts->fetch_assoc()) ? $row['total'] : 0;

$resTotalInventory = $conn->query("SELECT IFNULL(SUM(cantidad),0) AS total_cantidad FROM productos");
$totalInventory = ($row = $resTotalInventory->fetch_assoc()) ? $row['total_cantidad'] : 0;

$resLowStock = $conn->query("SELECT COUNT(*) AS low_count FROM productos WHERE cantidad < $LOW_STOCK_THRESHOLD");
$lowStockCount = ($row = $resLowStock->fetch_assoc()) ? $row['low_count'] : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inventario Distrilay - Dashboard</title>
<link rel="stylesheet" href="estilo.css">
</head>

<body>

<aside id="sidebar" class="sidebar open">
<div class="brand">
<button id="btnToggle" class="hamburger">☰</button>
<h1>Distrilay</h1>
</div>

<nav class="nav">
<a href="index.php" class="active">🏠 Dashboard</a>
<a href="productos.php">📦 Productos</a>
<a href="usuarios.php">👥 Usuarios</a>
<a href="informes.php">📊 Informes</a>
</nav>
</aside>


<main class="main">

<header class="main-header">
<h2>Bienvenido al Inventario de Distrilay</h2>
</header>


<section class="cards">

<article class="card">
<div class="card-title">Total de productos</div>
<div class="card-value"><?php echo $totalProducts; ?></div>
</article>

<article class="card">
<div class="card-title">Cantidad total en inventario</div>
<div class="card-value"><?php echo $totalInventory; ?></div>
</article>

<article class="card">
<div class="card-title">Productos con stock bajo (&lt; <?php echo $LOW_STOCK_THRESHOLD; ?>)</div>
<div class="card-value"><?php echo $lowStockCount; ?></div>
</article>

</section>



<section class="panel">

<h3>Productos recientes</h3>

<div class="table-wrap">

<table class="table">

<thead>
<tr>
<th>ID</th>
<th>Imagen</th>
<th>Nombre</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php
$q = $conn->query("SELECT * FROM productos ORDER BY id DESC LIMIT 8");

while($p = $q->fetch_assoc()):
?>

<tr>

<td><?php echo $p['id']; ?></td>

<td>

<?php if(!empty($p['imagen']) && file_exists(__DIR__.'/imagenes/'.$p['imagen'])): ?>

<img src="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" class="thumb">

<?php else: ?>

<div class="thumb placeholder">Sin foto</div>

<?php endif; ?>

</td>


<td><?php echo htmlspecialchars($p['nombre']); ?></td>

<td>$<?php echo number_format($p['precio'],2); ?></td>

<td><?php echo (int)$p['cantidad']; ?></td>


<td>

<a class="btn small view" title="Ver imagen"
href="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>"
target="_blank">👁</a>

<a class="btn small edit"
href="editar_producto.php?id=<?php echo $p['id']; ?>">✏️</a>

<a class="btn small danger"
onclick="return confirm('¿Eliminar producto?')"
href="eliminar.php?tipo=producto&id=<?php echo $p['id']; ?>">🗑</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</section>


<footer class="footer">
<small>Inventario Distrilay · PHP · MySQL · HTML · CSS · JS</small>
</footer>

</main>

<script src="script.js"></script>

</body>
</html>