<div class="flex justify-between items-center mb-10 flex-wrap gap-4 px-6 pt-6">
  <h1 class="text-3xl font-bold flex items-center gap-3 text-[#404141]">
    <i class="fas fa-users text-[#FEDF00] text-4xl"></i> <?= htmlspecialchars($title) ?>
  </h1>
  <form method="GET"
    class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-2xl shadow-md border border-gray-200">

    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>"
      placeholder="Buscar usuario o correo..."
      class="flex-1 min-w-[200px] px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#FEDF00] focus:outline-none">
    <select name="filter"
      class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#FEDF00] focus:outline-none">
      <option value="">Todos</option>
      <option value="user" <?= ($filter ?? '') === 'user' ? 'selected' : '' ?>>Usuarios</option>
      <option value="store" <?= ($filter ?? '') === 'store' ? 'selected' : '' ?>>Tiendas</option>
    </select>
    <button type="submit"
      class="px-5 py-2 bg-[#404141] text-[#FEDF00] font-semibold rounded-xl hover:bg-[#2f2f2f] transition flex items-center gap-2">
      <i class="fas fa-search"></i> Buscar
    </button>
  </form>
  <button type="button" onclick="document.getElementById('agregarUsuarioModal').showModal()"
    class="group flex items-center gap-2 bg-[#404141] hover:bg-[#2f2f2f] text-[#FEDF00] border-0 rounded-full shadow-md px-4 py-2 transition-all duration-300 overflow-hidden w-12 hover:w-60">
    <i class="fas fa-user-plus text-lg"></i>
    <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Agregar Usuario o
      Tienda</span>
  </button>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
  <?php foreach ($membersPaginated['items'] as $member): ?>
    <?php include __DIR__ . '/Partials/memberCard.php'; ?>
  <?php endforeach; ?>
</div>

<div class="flex justify-center gap-2 my-8">
  <?php include __DIR__ . '/Partials/pagination.php'; ?>
</div>

<?php include __DIR__ . '/Partials/modalAddMember.php'; ?>

<?php include __DIR__ . '/Partials/modalEditMember.php'; ?>

<?php
$errorMessages = [
  'missing_fields' => 'Por favor completa todos los campos requeridos.',
  'invalid_email' => 'Por favor ingresa un correo electrónico válido.',
];

$successMessages = [
  'updated' => 'Miembro actualizado con éxito.',
  'added' => 'Miembro agregado con éxito.',
];

$errorType = $_GET['error'] ?? null;
$errorMessage = $errorMessages[$errorType] ?? null;

$successType = $_GET['success'] ?? null;
$successMessage = $successMessages[$successType] ?? null;
?>

<?php if ($successMessage): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        icon: 'success',
        title: 'Proceso exitoso',
        html: '<?= addslashes($successMessage) ?>',
        confirmButtonColor: '#FFD700',
        confirmButtonText: 'Entendido'
      }).then(() => {
        window.location.href = '/users/';
      });
    });
  </script>
<?php elseif ($errorMessage): ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      Swal.fire({
        icon: 'error',
        title: 'Proceso fallido',
        html: '<?= addslashes($errorMessage) ?>',
        confirmButtonColor: '#FFD700',
        confirmButtonText: 'Entendido'
      }).then(() => {
        window.history.replaceState({}, '', '/users/');
      });
    });
  </script>
<?php endif; ?>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".form-activar").forEach(form => {
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        Swal.fire({
          title: '¿Estás seguro?',
          text: "Este cambio modificará el estado del miembro.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#404141',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>