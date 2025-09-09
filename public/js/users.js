function openEditModal(button) {
    const modal = document.getElementById("editarUsuarioModal");

    const id = button.dataset.id;
    const username = button.dataset.username;
    const email = button.dataset.email;
    const rol = button.dataset.rol;
    const type = button.dataset.type;
    const address = button.dataset.address;
    const city = button.dataset.city;

    document.getElementById("modalUserId").value = id;
    document.getElementById("modalUsername").value = username;
    document.getElementById("modalEmail").value = email;
    document.getElementById("modalRol").value = rol;

    if (type === "store") {
        document.getElementById("modalTitle").innerText = "Editar Tienda";
        document.getElementById("addressField").classList.remove("hidden");
        document.getElementById("cityField").classList.remove("hidden");
        document.getElementById("modalAddress").value = address;
        document.getElementById("modalCity").value = city;

        document.getElementById("modalUserId").value = "";
        document.getElementById("modalStoreId").value = id;
    } else {
        document.getElementById("modalTitle").innerText = "Editar Usuario";
        document.getElementById("addressField").classList.add("hidden");
        document.getElementById("cityField").classList.add("hidden");

        document.getElementById("modalUserId").value = id;
        document.getElementById("modalStoreId").value = "";
    }

    modal.showModal();
}

document.getElementById("roleSelect").addEventListener("change", function () {
    const roleSelect = document.getElementById("roleSelect");
    const userFields = document.getElementById("userFields");
    const storeFields = document.getElementById("storeFields");

    if (this.value == "3") {
        userFields.classList.add("hidden");
        storeFields.classList.remove("hidden");

        storeFields
            .querySelectorAll("input, select")
            .forEach((el) => (el.required = true));
        userFields
            .querySelectorAll("input")
            .forEach((el) => (el.required = false));
    } else {
        userFields.classList.remove("hidden");
        storeFields.classList.add("hidden");

        userFields
            .querySelectorAll("input")
            .forEach((el) => (el.required = true));
        storeFields
            .querySelectorAll("input, select")
            .forEach((el) => (el.required = false));
    }
});

roleSelect.dispatchEvent(new Event("change"));
