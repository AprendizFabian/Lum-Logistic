<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validador de Fechas de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f6fa;
      color: #2f3640;
    }
    .card {
      background-color: white;
      border: 1px solid #dcdde1;
      border-radius: 12px;
      color: #2f3640;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .form-control, .btn {
      border-radius: 8px;
    }
    .btn-primary {
      background-color: #4a69bd;
      border: none;
    }
    .btn-primary:hover {
      background-color: #3b57a2;
    }
    .btn-success {
      background-color: #44bd32;
    }
    .btn-danger {
      background-color: #e84118;
    }
    .section-title {
      margin-top: 40px;
      margin-bottom: 20px;
      font-size: 1.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h2 class="text-center mb-5">Validador de Productos</h2>

  <!-- Formulario principal -->
  <div class="card p-4 mb-4">
    <h5 class="card-title">Validar Producto</h5>
    <form>
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="ean" class="form-label">Código EAN</label>
          <input type="text" class="form-control" id="ean" placeholder="Ingrese el código EAN">
        </div>
        <div class="col-md-4">
          <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
          <input type="date" class="form-control" id="fecha_vencimiento">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalResultado">Calcular</button>
        </div>
      </div>

      <!-- Resultados -->
      <div class="row">
        <div class="col-md-4">
          <div class="card p-3">
            <strong>Fecha de Bloqueo:</strong>
            <p class="mb-0 text-muted">DD/MM/AAAA</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3">
            <strong>Días de Vida Útil:</strong>
            <p class="mb-0 text-muted">-- días</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3">
            <strong>Estado del Producto:</strong>
            <p class="mb-0 text-success">Vigente</p>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="card p-4 mb-4">
  <h5 class="card-title">Conversor de Fecha Juliana</h5>
  <form method="POST" action="/convertir-fecha">
    <div class="row g-3">
      <div class="col-md-6">
        <input type="text" class="form-control" name="fecha_juliana" placeholder="Ingrese fecha Juliana (Ej: 24198)" required>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary w-100" type="submit">Convertir</button>
      </div>
      <div class="col-md-3">
        <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["fecha_juliana"])): ?>
          <?php
            function convertirJuliana($fechaJuliana) {
              if (!preg_match('/^\d{5}$/', $fechaJuliana)) return 'Formato inválido';
              $año = substr($fechaJuliana, 0, 2);
              $diaDelAño = substr($fechaJuliana, 2);
              $añoFull = ($año < 50) ? '20' . $año : '19' . $año;
              $fecha = DateTime::createFromFormat('Y z', "$añoFull " . ($diaDelAño - 1));
              return $fecha ? $fecha->format('d/m/Y') : 'Formato inválido';
            }
            $resultado = convertirJuliana($_POST["fecha_juliana"]);
          ?>
          <div class="card p-2">
            <strong>Resultado:</strong>
            <p class="mb-0 <?= ($resultado === 'Formato inválido') ? 'text-danger' : 'text-muted' ?>">
              <?= $resultado ?>
            </p>
          </div>
        <?php else: ?>
          <div class="card p-2">
            <strong>Resultado:</strong>
            <p class="mb-0 text-muted">DD/MM/AAAA</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </form>
</div>

<div class="row text-center mb-4">
  <H2>Descargar bloqueos de hoy</H2>
  <div class="col-md-6 mb-2">
    <button onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=416131206'" class="btn btn-success w-100">
      📥 Bloqueos de hoy (.xls)
    </button>
  </div>
  <div class="col-md-6 mb-2">
    <button onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=416131206'" class="btn btn-danger w-100">
      📥 Bloqueos de hoy (.pdf)
    </button>
  </div>
</div>

<div class="row text-center mb-4">
  <H2>Descargar proximos de la semana </H2>
  <div class="col-md-6 mb-2">
    <button onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=xlsx&gid=115512917'" class="btn btn-success w-100">
      📥 Descargar bloqueos de proxima semana (.xls)
    </button>
  </div>
  <div class="col-md-6 mb-2">
    <button onclick="window.location.href='https://docs.google.com/spreadsheets/d/1zml_Q9YT5RVzBJoxs3rhqXz757dXaTaa7xcEStR0Rz0/export?format=pdf&gid=115512917'" class="btn btn-danger w-100">
      📥 Descargar bloqueos de la proxima semana(.pdf)
    </button>
  </div>
</div>

<div class="modal fade" id="modalResultado" tabindex="-1" aria-labelledby="modalResultadoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalResultadoLabel">Resultado del Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>EAN:</strong> 0123456789123</p>
        <p><strong>Fecha de Vencimiento:</strong> 25/12/2025</p>
        <p><strong>Fecha de Bloqueo:</strong> 10/12/2025</p>
        <p><strong>Días de Vida Útil:</strong> 90</p>
        <p><strong>Estado:</strong> <span class="text-success">Vigente</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
