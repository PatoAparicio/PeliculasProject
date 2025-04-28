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
                <i class="bi bi-list-task m-2"></i>CATEGORÍAS
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
                    <button type="button"  class="btn btn-primary" @click="nuevoRegistro()">
                      <i class="bi bi-plus-circle m-2"></i>Agregar
                    </button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="table-responsive">
                    <table
                      class="table align-items-center justify-content-center text-sm" >
                      <thead class="table-gray-personalizado">
                        <tr class="text-white">
                          <th class="text-uppercase font-weight-bolder text-center">
                            Item
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center" >
                            Nombre
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center" >
                            Estado
                          </th>
                          <th class="text-uppercase font-weight-bolder text-center" >
                            Acciones
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="datos.length === 0">
                          <td colspan="4" class="text-center">
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
    </div>
  </main>
</template>

<script setup>
import navbarLayout from "@/layout/navbarLayout.vue";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { indexCategoria, changeStatus } from "@/services/CategoriaServices.js";
import Swal from "sweetalert2";
const router = useRouter();
const datos = ref([]);
const dato = ref([]);
const formulario = ref({
  nombre: "",
});

onMounted(() => {
  listar();
});
const listar = async () => {
  try {
    const { data } = await indexCategoria();
    datos.value = data.datos;
  } catch (error) {}
};

const nuevoRegistro = () => {
  router.push({ path: "/categorias-form" });
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
  router.push({ path: `/categorias-form/${param}` });
};
</script>