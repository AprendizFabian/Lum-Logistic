<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo - Mi CRM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f5f7;
      margin: 0;
    }

    .sidebar {
      background-color: #2f4050;
      min-height: 100vh;
      padding: 1.5rem 1rem;
      color: white;
      width: 230px;
    }

    .sidebar h4 {
      color: white;
      margin-bottom: 2rem;
    }

    .sidebar a {
      display: block;
      color: white;
      margin: 10px 0;
      text-decoration: none;
      padding: 8px;
      border-radius: 5px;
    }

    .sidebar a:hover {
      background-color: #1c2b3a;
    }

    .topbar {
      background-color: #e9ecef;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #dee2e6;
    }

    .btn-agregar {
      background-color: #f0c000;
      border: none;
      color: black;
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 6px;
    }

    .btn-agregar:hover {
      background-color: #e6b800;
    }

    .icono-editar {
      color: #f4b400;
      cursor: pointer;
    }

    .icono-eliminar {
      color: #e53935;
      cursor: pointer;
    }

    .main-content {
      padding: 2rem;
      background-color: #f4f5f7;
      flex-grow: 1;
    }

    .search-box {
      width: 250px;
    }
  </style>
</head>
<body>
  <div class="d-flex">
  
    <aside class="sidebar">
      <h4>Mi CRM</h4>
      <a href="index.html"><i class="bi bi-box"></i> Catálogo</a>
      <a href="vida_util.html"><i class="bi bi-clock-history"></i> Vida Útil</a>
      <a href="tabla_usuarios.html"><i class="bi bi-people"></i> Usuarios</a>
      <a href="configuracion.html"><i class="bi bi-gear"></i> Configuración</a>
    </aside>

    <div class="flex-grow-1">
 
      <div class="topbar">
        <h5 class="mb-0">Catálogo</h5>
        <div class="d-flex align-items-center gap-3">
          <input type="text" class="form-control search-box" placeholder="Buscar...">
       <a href="perfil.html" class="btn btn-outline-dark d-flex align-items-center gap-2">
  <i class="bi bi-person-circle"></i> <span>Admin</span>
</a>

        </div>
      </div>

      <!-- Main content -->
      <div class="main-content">
        <!-- Botón que abre el modal -->
        <button class="btn btn-agregar mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">
          <i class="bi bi-plus-circle"></i> Agregar nuevo
        </button>

        <!-- Tabla -->
        <div class="table-responsive">
          <table class="table table-bordered bg-white">
            <thead class="table-light">
              <tr>
                <th>ID_</th>
                <th>Syncid</th>
                <th>EAN</th>
                <th>Descripción</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>736354</td>
                <td>9736382637</td>
                <td>papa de papa</td>
              </tr>
              <!-- Más filas aquí -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL AGREGAR -->
  <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form>
          <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarLabel">Agregar Vida Útil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="id_vida_util" class="form-label">ID Vida Útil</label>
              <input type="text" class="form-control" id="id_vida_util" required>
            </div>
            <div class="mb-3">
              <label for="concepto" class="form-label">Concepto</label>
              <input type="text" class="form-control" id="concepto" required>
            </div>
            <div class="mb-3">
              <label for="tiempo" class="form-label">Tiempo</label>
              <input type="text" class="form-control" id="tiempo" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
