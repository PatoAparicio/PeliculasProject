<template>
  <header>
    <navbarLayout />
  </header>
  <main>
    <div class="container">
      <div class="row gy-3">
        <div class="col-12">
          <div class="card border border-0 shadow-lg">
            <div class="card-body text-center">
              <h5 class="card-title titulo-especial">
                <i class="bi bi-film m-2"></i>PELÍCULAS
              </h5>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="row gy-2">
                <div class="col-12">
                  <div class="text-center">
                    <button type="button" class="btn btn-primary" @click="nuevoRegistro()">
                      <i class="bi bi-plus-circle m-2"></i>Agregar
                    </button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table align-items-center justify-content-center text-sm">
                      <thead class="table-gray-personalizado">
                        <tr class="text-white">
                          <th class="text-uppercase font-weight-bolder text-center">
                            Item
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Título
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Descripción
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Precio
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Imagen
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Categoría
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Estado
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center">
                            Acciones
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="datos.length === 0">
                          <td colspan="7" class="text-center">
                            No se encuentran datos disponibles
                          </td>
                        </tr>
                        <tr v-for="(dato, indice) in datos" :key="indice">
                          <td class="text-center">
                            {{ indice + 1 }}
                          </td>
                          <td class="text-center">
                            {{ dato.nombre }}
                          </td>
                          <td class="text-center">
                            {{ truncarDescripción(dato.descripcion, 50) }}...
                          </td>
                          <td class="text-center">
                            {{ dato.precio }}
                          </td>
                          <td class="text-center">
                            <img
                              :src="`${ASSET_BASE_URL}${dato.imagen}`"
                              :alt="dato.imagen"
                              width="60px"
                              height="60px"
                            />
                          </td>
                          <td class="text-center">
                            {{ dato.categoria.nombre }}
                          </td>
                          <td class="text-center">
                            <span
                              class="badge"
                              :class="
                                dato.is_deleted ? 'bg-danger' : 'bg-success'
                              "
                              >{{
                                dato.is_deleted ? "Inactivo" : "Activo"
                              }}</span
                            >
                          </td>
                          <td class="text-center">
                            <div class="btn-group">
                              <i
                                class="bi bi-eye text-warning btn-action mx-1 icon"
                                @click="verDetalle(dato.id)"
                              ></i>
                              <i
                                class="bi bi-pencil text-primary btn-action cursor-pointer mx-1"
                                @click="editar(dato.id)"
                              ></i>
                              <i
                                class="btn-action cursor-pointer mx-1"
                                :class="{
                                  'bi bi-check2 text-success': dato.is_deleted,
                                  'bi bi-trash text-danger': !dato.is_deleted,
                                }"
                                @click="estado(dato.id)"
                              ></i>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div
        class="modal fade"
        id="staticBackdrop"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-primary-subtle">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">
                Detalle Película
              </h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="card border border-5">
                <img
                  :src="`${ASSET_BASE_URL}${dato.imagen}`"
                  :alt="dato.imagen"
                  class="card-img-top"
                />
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h6>
                        <span
                          class="badge"
                          :class="dato.is_deleted ? 'bg-danger' : 'bg-success'"
                          >{{ dato.is_deleted ? "Inactivo" : "Activo" }}</span
                        >
                      </h6>
                      <h3 class="card-title">{{ dato.nombre }}</h3>
                      <p class="card-text">{{ dato.descripcion }}</p>
                      <h4 class="card-text">Precio: {{ dato.precio }}</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-danger"
                data-bs-dismiss="modal"
              >
                Cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import navbarLayout from "@/layout/navbarLayout.vue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { index, changeStatus, show } from "@/services/PeliculasServices";
import { ASSET_BASE_URL } from "@/services/Http";
import Swal from "sweetalert2";
import { Modal } from "bootstrap/dist/js/bootstrap.bundle";
const router = useRouter();
const datos = ref([]);
const dato = ref({});
let modal = null;
let instanciaModal = null;
const formulario = ref({
  nombre: "",
  descripcion: "",
  precio: "",
  categoria: "",
  imagen: null,
});

onMounted(() => {
  listar();
  modal = document.getElementById("staticBackdrop");
  instanciaModal = new Modal(modal);
});
const listar = async () => {
  try {
    const { data } = await index();
    datos.value = data.datos;
  } catch (error) {
    //console.log(error);
  }
};

const nuevoRegistro = () => {
  router.push({ path: "/peliculas-form" });
};

const estado = async (param) => {
  try {
    Swal.fire({
      title: "¿Está seguro de cambiar el estado?",
      text: "Está a punto de cambiar el estado del registro",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, cambiar.",
      cancelButtonText: "¡No, cancelar!",
    }).then(async (result) => {
      if (result.isConfirmed) {
        const { data } = await changeStatus(param);
        Swal.fire({
          title: "¡Bien!",
          text: "Estado cambiado.",
          icon: "success",
        });
      }
      listar();
    });
  } catch (error) {
    //console.log(error);
  }
};

const editar = (param) => {
  router.push({ path: `/peliculas-form/${param}` });
};

const verDetalle = async (param) => {
  try {
    const { data } = await show(param);
    dato.value = data.datos;
    instanciaModal.show();
  } catch (error) {
    //console.log(error);
  }
};

const truncarDescripción = (description, length) => {
  if (description === null) {
    return "";
  }
  return description.substring(0, length);
};
</script>