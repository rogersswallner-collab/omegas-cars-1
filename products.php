<?php
include('includes/auth.php');
include('includes/db.php');

// Eliminar producto
if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $conn->query("DELETE FROM productos WHERE id = $id");
  header('Location: products.php');
  exit;
}

// Actualizar producto
if (isset($_POST['actualizar'])) {
  $id = intval($_POST['id']);
  $marca = $_POST['marca'];
  $descripcion = $_POST['descripcion'];
  $precio = $_POST['precio'];
  $precio_descuento = !empty($_POST['precio_descuento']) ? $_POST['precio_descuento'] : 'NULL';
  $categoria = $_POST['categoria'];
  $stock = $_POST['stock'];

  $sql = "UPDATE productos SET
          marca = '$marca',
          descripcion = '$descripcion',
          precio = '$precio',
          precio_descuento = $precio_descuento,
          categoria = '$categoria',
          stock = '$stock'
          WHERE id = $id";
  $conn->query($sql);
  header('Location: products.php');
  exit;
}

// Guardar nuevo producto
if (isset($_POST['guardar'])) {
  $marca = $_POST['marca'];
  $descripcion = $_POST['descripcion'];
  $precio = $_POST['precio'];
  $precio_descuento = !empty($_POST['precio_descuento']) ? $_POST['precio_descuento'] : 'NULL';
  $categoria = $_POST['categoria'];
  $stock = $_POST['stock'];

  $sql = "INSERT INTO productos (marca, descripcion, precio, precio_descuento, categoria, stock)
          VALUES ('$marca', '$descripcion', '$precio', $precio_descuento, '$categoria', '$stock')";
  $conn->query($sql);
  header('Location: products.php');
  exit;
}

// Obtener producto a editar
$productoEditar = null;
if (isset($_GET['editar'])) {
  $id = intval($_GET['editar']);
  $result = $conn->query("SELECT * FROM productos WHERE id = $id");
  $productoEditar = $result->fetch_assoc();
}

// Obtener productos
$productos = $conn->query("SELECT * FROM productos ORDER BY marca, precio ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de productos - Omega Cars</title>
  <link rel="stylesheet" href="assets/css/products.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Gestión de Productos</h2>
      <a href="dashboard.php" class="back-link">← Volver al Dashboard</a>
    </div>

    <div class="form-section">
      <h2><?php echo $productoEditar ? 'Editar Producto' : 'Agregar Nuevo Producto'; ?></h2>
      <form method="POST">
        <?php if ($productoEditar): ?>
          <input type="hidden" name="id" value="<?php echo $productoEditar['id']; ?>">
        <?php endif; ?>

        <input type="text" name="marca" placeholder="Marca" required
               value="<?php echo $productoEditar ? htmlspecialchars($productoEditar['marca']) : ''; ?>">

        <input type="text" name="descripcion" placeholder="Descripción" required
               value="<?php echo $productoEditar ? htmlspecialchars($productoEditar['descripcion']) : ''; ?>">

        <div class="precio-grid">
          <div class="precio-field">
            <label>Precio Original</label>
            <input type="number" step="0.01" name="precio" placeholder="Precio original" required
                   value="<?php echo $productoEditar ? $productoEditar['precio'] : ''; ?>">
          </div>

          <div class="precio-field">
            <label>Precio con Descuento (opcional)</label>
            <input type="number" step="0.01" name="precio_descuento" placeholder="Precio con descuento"
                   value="<?php echo $productoEditar && $productoEditar['precio_descuento'] ? $productoEditar['precio_descuento'] : ''; ?>">
          </div>
        </div>

        <input type="text" name="categoria" placeholder="Categoría (opcional)"
               value="<?php echo $productoEditar ? htmlspecialchars($productoEditar['categoria']) : ''; ?>">

        <input type="number" name="stock" placeholder="Stock (opcional)"
               value="<?php echo $productoEditar ? $productoEditar['stock'] : ''; ?>">

        <div class="form-buttons">
          <?php if ($productoEditar): ?>
            <button type="submit" name="actualizar">Actualizar Producto</button>
            <a href="products.php" class="btn-cancelar">Cancelar</a>
          <?php else: ?>
            <button type="submit" name="guardar">Guardar Producto</button>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <div class="catalogo-section">
      <h2>Catálogo de Productos</h2>
      <div class="catalogo">
        <?php if ($productos->num_rows > 0): ?>
          <?php while ($p = $productos->fetch_assoc()): ?>
            <div class="tarjeta">
              <h3><?php echo htmlspecialchars($p['marca']); ?></h3>
              <p><?php echo htmlspecialchars($p['descripcion']); ?></p>

              <div class="precios-container">
                <?php if ($p['precio_descuento']): ?>
                  <p class="precio-original">$<?php echo number_format($p['precio'], 2); ?></p>
                  <p class="precio-descuento">$<?php echo number_format($p['precio_descuento'], 2); ?></p>
                  <?php
                    $descuento_porcentaje = (($p['precio'] - $p['precio_descuento']) / $p['precio']) * 100;
                  ?>
                  <span class="etiqueta-descuento">-<?php echo round($descuento_porcentaje); ?>%</span>
                <?php else: ?>
                  <p class="precio">$<?php echo number_format($p['precio'], 2); ?></p>
                <?php endif; ?>
              </div>

              <?php if ($p['categoria']): ?>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($p['categoria']); ?></p>
              <?php endif; ?>
              <?php if ($p['stock'] !== null): ?>
                <p><strong>Stock:</strong> <?php echo $p['stock']; ?> unidades</p>
              <?php endif; ?>

              <div class="acciones">
                <a href="products.php?editar=<?php echo $p['id']; ?>" class="btn-editar">Editar</a>
                <a href="products.php?eliminar=<?php echo $p['id']; ?>"
                   class="btn-eliminar"
                   onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty-state">
            No hay productos registrados aún. ¡Agrega tu primer producto!
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
