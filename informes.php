<?php
include 'conexion.php';

$LOW_STOCK_THRESHOLD = 5;

// Productos con stock bajo
$low = $conn->query("SELECT * FROM productos WHERE cantidad < $LOW_STOCK_THRESHOLD ORDER BY cantidad ASC");

// Cantidades totales
$totales = $conn->query('SELECT IFNULL(SUM(cantidad),0) AS total_cantidad, COUNT(*) AS total_productos FROM productos');
$t = $totales->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Informes - Inventario Distrilay</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <aside id="sidebar" class="sidebar">
    <div class="brand">
      <button id="btnToggle" class="hamburger">☰</button>
      <h1>Distrilay</h1>
    </div>
    <nav class="nav">
      <a href="index.php"><span class="icon">🏠</span><span class="label">Dashboard</span></a>
      <a href="productos.php"><span class="icon">📦</span><span class="label">Productos</span></a>
      <a href="usuarios.php"><span class="icon">👥</span><span class="label">Usuarios</span></a>
      <a href="informes.php" class="active"><span class="icon">📊</span><span class="label">Informes</span></a>
    </nav>
  </aside>

  <main class="main">
    <header class="main-header">
      <h2>Informes</h2>
      <p class="muted">Resumen de inventario y productos con stock bajo</p>
    </header>

    <section class="cards">
      <article class="card">
        <div class="card-title">Total de productos</div>
        <div class="card-value"><?php echo $t['total_productos']; ?></div>
      </article>
      <article class="card">
        <div class="card-title">Total en inventario</div>
        <div class="card-value"><?php echo $t['total_cantidad']; ?></div>
      </article>
    </section>

    <section class="panel">
      <h3>Productos con stock bajo (&lt; <?php echo $LOW_STOCK_THRESHOLD; ?>)</h3>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while($p = $low->fetch_assoc()): ?>
            <tr id="producto-<?php echo $p['id']; ?>">
              <td><?php echo $p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['nombre']); ?></td>
              <td><?php echo (int)$p['cantidad']; ?></td>
              <td>
                <?php if(!empty($p['imagen']) && file_exists(__DIR__.'/imagenes/'.$p['imagen'])): ?>
                  <a class="btn small view" title="Ver imagen" href="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" target="_blank" rel="noopener">Ver</a>
                <?php else: ?>
                  <span class="btn small disabled">Ver</span>
                <?php endif; ?>
                <a class="btn small danger" href="eliminar.php?tipo=producto&id=<?php echo $p['id']; ?>">Eliminar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel">
      <h3>Tabla completa de productos</h3>
      <div class="table-wrap">
        <?php
        // PAGINACIÓN para informes
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;
        $totalRes = $conn->query('SELECT COUNT(*) AS tot FROM productos');
        $totalRow = $totalRes->fetch_assoc();
        $totalItems = $totalRow ? (int)$totalRow['tot'] : 0;
        $totalPages = max(1, ceil($totalItems / $perPage));

        $all = $conn->query("SELECT * FROM productos ORDER BY nombre ASC LIMIT $offset, $perPage");
        ?>

        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <?php while($a = $all->fetch_assoc()): ?>
            <tr>
              <td><?php echo $a['id']; ?></td>
              <td>
                <?php if(!empty($a['imagen']) && file_exists(__DIR__.'/imagenes/'.$a['imagen'])): ?>
                  <img src="imagenes/<?php echo htmlspecialchars($a['imagen']); ?>" class="thumb" alt="">
                <?php else: ?>
                  <div class="thumb placeholder">Sin foto</div>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($a['nombre']); ?></td>
              <td>$<?php echo number_format($a['precio'],2); ?></td>
              <td><?php echo (int)$a['cantidad']; ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- paginador informes -->
        <div class="paginator">
          <?php if($page > 1): ?>
            <a class="btn" href="informes.php?page=<?php echo $page-1; ?>">Anterior</a>
          <?php else: ?>
            <span class="btn disabled">Anterior</span>
          <?php endif; ?>

          <?php for($i=1;$i<=$totalPages;$i++): ?>
            <?php if($i == $page): ?>
              <span class="btn primary"><?php echo $i; ?></span>
            <?php else: ?>
              <a class="btn" href="informes.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if($page < $totalPages): ?>
            <a class="btn" href="informes.php?page=<?php echo $page+1; ?>">Siguiente</a>
          <?php else: ?>
            <span class="btn disabled">Siguiente</span>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <footer class="footer">
      <small>Inventario Distrilay - Informes</small>
    </footer>
  </main>

  <script src="script.js"></script>
</body>
</html>
