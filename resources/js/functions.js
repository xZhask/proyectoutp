const lnkProfesionales = document.querySelector("#lnk-profesionales");
const lnkHorarios = document.querySelector("#lnk-horarios");
const lnkPacientes = document.querySelector("#lnk-pacientes");
const lnkAtenciones = document.querySelector("#lnk-atenciones");
const lnkCitas = document.querySelector("#lnk-citas");
const lnkReportes = document.querySelector("#lnk-reportes");
const lnkServicios = document.querySelector("#lnk-servicios");
const frmProfesional = document.querySelector("#FrmProfesional");

function abrir_seccion(lnk) {
  $(".list-menu .active").removeClass("active");
  $(`#${lnk.id}`).addClass("active");
  fetch(`App/views/${lnk.attributes.href.nodeValue}`)
    .then((res) => res.text())
    .then((res) => $("#wrapper-section").html(res));
}
/* SECCIONES */

lnkProfesionales.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkProfesionales);
  ListarPersonas("E");
});
lnkHorarios.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkHorarios);
});
lnkPacientes.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkPacientes);
  ListarPersonas("P");
});
lnkAtenciones.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkAtenciones);
});
lnkCitas.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkCitas);
});
lnkReportes.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkReportes);
});
lnkServicios.addEventListener("click", (e) => {
  e.preventDefault();
  abrir_seccion(lnkServicios);
});

/* -------------------------- EVENTOS --------------------------------------------------- */
function cambiarFile() {
  const input = document.getElementById("foto");
  if (input.files && input.files[0]) archivo = input.files[0].name;
  console.log("File Seleccionado : ", input.files[0].name);
  document.getElementById(
    "lbltextfile"
  ).innerHTML = `<p>${archivo}</p><i class="fa-solid fa-cloud-arrow-up"></i>`;
}
$(function () {
  $(document).on("click", "#BtnAddProfesional", function (event) {
    mostrarmodal("Registro de Profesional");
    $("#FrmProfesional").css("display", "block");
    $("#accionProfesional").val("REGISTRAR_PERSONA");
    $("#nrodocProf").prop("readonly", false);
  });
});
$(function () {
  $(document).on("click", "#BtnAddPaciente", function (event) {
    mostrarmodal("Registro de Paciente");
    $("#FrmPaciente").css("display", "block");
  });
});
$(function () {
  $(document).on("click", "#BtnAddHorario", function (event) {
    mostrarmodal("Registro de Horario");
    $("#FrmHorario").css("display", "block");
  });
});
$(function () {
  $(document).on("click", "#BtnAddCita", function (event) {
    mostrarmodal("Registro de Cita");
    $("#FrmCita").css("display", "block");
  });
});
$(function () {
  $(document).on("click", "#tbhorario .disponible", function (event) {
    text = $(event.target).text();
    fecha = $(this).prop("title");
    mostrarmodal("Registro de Cita <br> " + fecha + " " + text);
    $("#FrmCita").css("display", "block");
  });
});
$(function () {
  $(document).on("click", "#BtnCambioAvatar", function (event) {
    mostrarmodal("Cambiar Foto");
    $("#FrmCambioAvatar").css("display", "block");
  });
});
$(function () {
  $(document).on("click", "#BtnAddServicio", function (event) {
    mostrarmodal("Agregar Servicio");
    $("#FrmServicio").css("display", "block");
  });
});
function mostrarmodal(texto) {
  $("#modal").css("display", "table");
  $("#modal").css("position", "absolute");
  $("form").css("display", "none");
  document.getElementById("TittleForm").innerHTML = texto;
}
function cerrarModal() {
  $("#modal").css("display", "none");
  $(".modal-content").addClass("w650");
  LimpiarFormularios();
  $(".cont-datos-nvisible").removeClass("active");
  $(".cont-DatosPacienteCita").removeClass("oculto");
}
$(".closebtn").click(function (event) {
  cerrarModal();
});
$(".close-modal-area").click(function (event) {
  event.preventDefault();
  cerrarModal();
});
$(function () {
  $(document).on("click", ".add-atention", function (event) {
    $(".modal-content").removeClass("w650");
    mostrarmodal("Registro de Atención");
    $("#FrmAtencion").css("display", "block");
  });
});
$(function () {
  $(document).on("click", ".add-procedure", function (event) {
    $(".modal-content").removeClass("w650");
    mostrarmodal("Registro de Procedimiento");
    $("#FrmProcedimiento").css("display", "block");
  });
});
$(function () {
  $(document).on("click", ".add-pago", function (event) {
    mostrarmodal("Registrar Pago");
    $("#FrmPago").css("display", "block");
  });
});
function LimpiarFormularios() {
  document.getElementById("FrmAtencion").reset();
  document.getElementById("FrmCita").reset();
  document.getElementById("FrmHorario").reset();
  document.getElementById("FrmProfesional").reset();
  document.getElementById("FrmPaciente").reset();
  document.getElementById("FrmServicio").reset();
}
/* --------------------------- FUNCIONES ---------------------------------------------------- */

async function postData(data) {
  const response = await fetch("App/controllers/controllerPersona.php", {
    method: "POST",
    body: data,
    cache: "no-cache",
  });
  return response;
}
async function ListarPersonas(tipoPersona) {
  let datos = new FormData();
  datos.append("accion", "LISTAR_PERSONAS");
  datos.append("tipoPersona", tipoPersona);
  const personas = await (await postData(datos)).json();
  renderProfesionales(personas);
}
function renderProfesionales(personas) {
  const listPersonas = personas.map(
    (persona) =>
      `<tr><td>${persona.nombre}</td><td>${persona.nro_doc}</td><td>${persona.n_colegiatura}</td><td>${persona.telefono}</td><td>${persona.email}</td><td><i class='fa-solid fa-user-pen icon-green edit-user'></i></td></tr>`
  );
  const cadenaProfesionales = JSON.stringify(listPersonas);
  $("#tbProfesionales").html(cadenaProfesionales);
}
/* $(function () {
  $(document).on("click", "#btn-btnSearchProf", function (e) {
    let dni = $("#nrodocProf").val();
    if (dni !== "") {
      let datos = new FormData();
      datos.append("accion", "CONSULTA_DNI");
      datos.append("dni", dni);
      postData(datos)
        .then((res) => res.json())
        .then((res) => {
          if (res.success) {
            let respuesta = res.data;
            $("#nombreProfesional").val(respuesta.nombre_completo);
            $("#direccion").val(respuesta.direccion);
            $("#departamento").val(respuesta.departamento);
            $("#provincia").val(respuesta.provincia);
          }
        });
    } else alert("INGRESE N° DE DOCUMENTO A BUSCAR");
  });
});

frmProfesional.addEventListener("submit", (e) => {
  e.preventDefault();
  let form = new FormData(frmProfesional);
  postData(form)
    .then((res) => res.text())
    .then((res) => {
      if (res == "REGISTRADO") {
        Swal.fire(`Se ha ${res} correctamente`, "", "success");
        cerrarModal();
        ListarProfesionales();
      } else Swal.fire("No se Registró", res, "error");
    });
});
function OcultarSelectItem() {
  $(".item-toggle").toggle("normal");
  $(".opciones-list").toggleClass("Active");
  $(".btn-list-toggle").toggleClass("activo");
}
function CargarSelectProfesionales() {
  let datos = new FormData();
  datos.append("accion", "CARGAR_SELECT_PROFESIONALES");
  postData(datos)
    .then((res) => res.text())
    .then((res) => $("#list-profesionales").html(res));
}
function SeleccionarItem(id) {
  $("#IdProfesional").val(id);
  let texto = document.getElementById(`${id}`).innerHTML;
  document.getElementById(
    "BtnDivSelect"
  ).innerHTML = `${texto} < i class= "fa-solid fa-chevron-down" ></i > `;
  OcultarSelectItem();
}

$(function () {
  $(document).on("click", "#tbprofesionales .edit-user", function (e) {
    e.preventDefault();
    let parent = $(this).closest("table");
    let tr = $(this).closest("tr");
    let nrodoc = $(tr).find("td").eq(2).html();
    mostrarmodal("Registro de Profesional");
    $("#FrmProfesional").css("display", "block");
    $("#accionProfesional").val("EDITAR_PERSONA");
    let datos = new FormData();
    datos.append("accion", "BUSCAR_PERSONA");
    datos.append("nrodoc", nrodoc);
    postData(datos)
      .then((res) => res.json())
      .then((res) => {
        console.log(res[0]);
        $("#nombreProfesional").val(res[0].nombre);
        $("#direccion").val(res[0].direccion);
        $("#provincia").val(res[0].prov_ubigeo);
        $("#departamento").val(res[0].depart_ubigeo);
        $("#fechaNac").val(res[0].fecha_nac);
        $("#colegiatura").val(res[0].n_colegiatura);
        $("#nrodocProf").val(res[0].nro_doc);
        $("#email").val(res[0].email);
        $("#telefono").val(res[0].telefono);
        $("#nrodocProf").prop("readonly", "true");
      });
  });
});
 */
