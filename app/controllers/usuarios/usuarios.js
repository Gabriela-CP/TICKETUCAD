$(function () {
    cargar_roles();
    listar_usuarios();

    let timer;
    $("#inp_buscar").on("keyup", function () {
        clearTimeout(timer);
        timer = setTimeout(listar_usuarios, 400);
    });

    $("#btn_agregar").on("click", function () {
        $("#modal_crear").modal("show");
    });

    $("#form_crear").on("submit", function (e) {
        e.preventDefault();
        crear_usuario();
    });

    $("#form_editar").on("submit", function (e) {
        e.preventDefault();
        guardar_edicion();
    });
});

// ─── Roles ────────────────────────────────────────────────────────────────────

function cargar_roles() {
    $.ajax({
        url: "../../models/usuarios/usuarios.php",
        method: "POST",
        data: { accion: "listar_roles" },
        dataType: "json",
    }).done(function (response) {
        if (response.success) {
            let opts = '<option value="">-- Selecciona un rol --</option>';
            for (let i = 0; i < response.data.length; i++) {
                opts += "<option value='" + response.data[i].id + "'>" + response.data[i].nombre + "</option>";
            }
            $("#crear_rol").html(opts);
            $("#editar_rol").html(opts);
        }
    });
}

// ─── Listar ───────────────────────────────────────────────────────────────────

function listar_usuarios() {
    $.ajax({
        url: "../../models/usuarios/usuarios.php",
        method: "POST",
        data: {
            accion:   "listar",
            busqueda: $("#inp_buscar").val()
        },
        dataType: "json",
    }).done(function (response) {
        if (response.success) {
            let filas = "";

            for (let i = 0; i < response.total; i++) {
                let u         = response.data[i];
                let iniciales = obtener_iniciales(u.nombre);
                let badge_rol = obtener_badge_rol(u.rol);
                let badge_est = u.estado === "activo"
                    ? '<span class="badge badge-success">Activo</span>'
                    : '<span class="badge badge-danger">Inactivo</span>';
                let fecha = u.fecha_creacion ? u.fecha_creacion.substring(0, 10) : "-";
                let nom   = u.nombre.replace(/'/g, "\\'");
                let cor   = u.correo.replace(/'/g, "\\'");
                let usr   = u.usuario.replace(/'/g, "\\'");

                filas +=
                    "<tr>" +
                        "<td class='text-muted'>#" + u.id + "</td>" +
                        "<td>" +
                            "<div class='d-flex align-items-center'>" +
                                "<span class='badge badge-primary mr-2 p-2'>" + iniciales + "</span>" +
                                "<strong>" + u.nombre + "</strong>" +
                            "</div>" +
                        "</td>" +
                        "<td class='text-muted'>" + u.correo + "</td>" +
                        "<td class='text-center'>" + badge_rol + "</td>" +
                        "<td class='text-center'>" + badge_est + "</td>" +
                        "<td class='text-muted'>" + fecha + "</td>" +
                        "<td class='text-right'>" +
                            "<button class='btn btn-sm btn-outline-primary mr-1' title='Editar' " +
                                "onclick=\"abrir_editar(" + u.id + ",'" + nom + "','" + cor + "','" + usr + "'," + u.rol_id + ")\">" +
                                "<i class='fas fa-edit'></i>" +
                            "</button>" +
                            "<button class='btn btn-sm btn-outline-warning mr-1' title='Cambiar estado' " +
                                "onclick=\"cambiar_estado(" + u.id + ",'" + u.estado + "')\">" +
                                "<i class='fas fa-exchange-alt'></i>" +
                            "</button>" +
                            "<button class='btn btn-sm btn-outline-danger' title='Eliminar' " +
                                "onclick=\"eliminar_usuario(" + u.id + ",'" + nom + "')\">" +
                                "<i class='fas fa-trash'></i>" +
                            "</button>" +
                        "</td>" +
                    "</tr>";
            }

            if (filas === "") {
                filas = "<tr><td colspan='7' class='text-center text-muted py-3'>No se encontraron usuarios.</td></tr>";
            }

            $("#tb_usuarios").html(filas);
            $("#lbl_total").text("Mostrando " + response.total + " usuario(s)");
        } else {
            Swal.fire("Error", response.message, "error");
        }
    }).fail(function (jqXHR, textStatus) {
        Swal.fire("Error", "No se pudo conectar con el servidor: " + textStatus, "error");
    });
}

// ─── Crear ────────────────────────────────────────────────────────────────────

function crear_usuario() {
    $.ajax({
        url: "../../models/usuarios/usuarios.php",
        method: "POST",
        data: {
            accion:   "crear",
            nombre:   $("#crear_nombre").val(),
            correo:   $("#crear_correo").val(),
            usuario:  $("#crear_usuario").val(),
            password: $("#crear_password").val(),
            rol_id:   $("#crear_rol").val(),
        },
        dataType: "json",
    }).done(function (response) {
        if (response.success) {
            $("#modal_crear").modal("hide");
            $("#form_crear")[0].reset();
            Swal.fire("Listo", response.message, "success");
            listar_usuarios();
        } else {
            Swal.fire("Error", response.message, "error");
        }
    }).fail(function (jqXHR, textStatus) {
        Swal.fire("Error", "No se pudo conectar con el servidor: " + textStatus, "error");
    });
}

// ─── Editar ───────────────────────────────────────────────────────────────────

function abrir_editar(id, nombre, correo, usuario, rol_id) {
    $("#editar_id").val(id);
    $("#editar_nombre").val(nombre);
    $("#editar_correo").val(correo);
    $("#editar_usuario").val(usuario);
    $("#editar_rol").val(rol_id);
    $("#editar_password").val("");
    $("#modal_editar").modal("show");
}

function guardar_edicion() {
    $.ajax({
        url: "../../models/usuarios/usuarios.php",
        method: "POST",
        data: {
            accion:   "editar",
            id:       $("#editar_id").val(),
            nombre:   $("#editar_nombre").val(),
            correo:   $("#editar_correo").val(),
            usuario:  $("#editar_usuario").val(),
            password: $("#editar_password").val(),
            rol_id:   $("#editar_rol").val(),
        },
        dataType: "json",
    }).done(function (response) {
        if (response.success) {
            $("#modal_editar").modal("hide");
            Swal.fire("Listo", response.message, "success");
            listar_usuarios();
        } else {
            Swal.fire("Error", response.message, "error");
        }
    }).fail(function (jqXHR, textStatus) {
        Swal.fire("Error", "No se pudo conectar con el servidor: " + textStatus, "error");
    });
}

// ─── Cambiar estado ───────────────────────────────────────────────────────────

function cambiar_estado(id, estado_actual) {
    let nuevo = estado_actual === "activo" ? "inactivo" : "activo";
    Swal.fire({
        title: "¿Cambiar estado?",
        text: "El usuario pasará a " + nuevo + ".",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#083466",
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: "../../models/usuarios/usuarios.php",
            method: "POST",
            data: { accion: "cambiar_estado", id: id },
            dataType: "json",
        }).done(function (response) {
            if (response.success) {
                Swal.fire("Listo", response.message, "success");
                listar_usuarios();
            } else {
                Swal.fire("Error", response.message, "error");
            }
        }).fail(function (jqXHR, textStatus) {
            Swal.fire("Error", "No se pudo conectar con el servidor: " + textStatus, "error");
        });
    });
}

// ─── Eliminar ─────────────────────────────────────────────────────────────────

function eliminar_usuario(id, nombre) {
    Swal.fire({
        title: "¿Eliminar usuario?",
        text: 'Se eliminará a "' + nombre + '" de forma permanente.',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: "../../models/usuarios/usuarios.php",
            method: "POST",
            data: { accion: "eliminar", id: id },
            dataType: "json",
        }).done(function (response) {
            if (response.success) {
                Swal.fire("Eliminado", response.message, "success");
                listar_usuarios();
            } else {
                Swal.fire("Error", response.message, "error");
            }
        }).fail(function (jqXHR, textStatus) {
            Swal.fire("Error", "No se pudo conectar con el servidor: " + textStatus, "error");
        });
    });
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

function obtener_iniciales(nombre) {
    return nombre.split(" ")
        .map(function (p) { return p[0]; })
        .join("")
        .substring(0, 2)
        .toUpperCase();
}

function obtener_badge_rol(rol) {
    let clases = {
        "Administrador": "badge-primary",
        "Técnico":       "badge-secondary",
        "Usuario":       "badge-light",
    };
    let cls = clases[rol] || "badge-light";
    return "<span class='badge " + cls + "'>" + (rol || "Sin rol") + "</span>";
}
