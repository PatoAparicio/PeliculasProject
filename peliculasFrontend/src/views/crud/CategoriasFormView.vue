<template>
  <header>
    <navbarLayout />
  </header>
  <main>
    <div class="container">
      <div class="row gy-3">
        <div class="col-12">
          <div class="card border border-0 shadow-lg">
            <div class="card-body">
              <h5 class="card-title font-weight-bolder">
                <i class="bi me-2" :class="idCategoria ? 'bi-pencil' : 'bi-floppy'" ></i>
                {{ idCategoria ? "Editar Registro" : "Nuevo Registro" }}
              </h5>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="row gy-3">
                <div class="col-12 col-md-6">
                  <label
                    for="nombre"
                    class="form-label font-weight-bolder text-sm"
                    >Título
                    <span class="text-danger fst-italic">*</span>
                  </label>
                  <input
                    type="text"
                    class="form-control border"
                    :class="{ 'border-danger': errors.nombre }"
                    id="nombre"
                    v-model="formulario.nombre" />
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.nombre" >
                    {{ errors.nombre[0] }}
                  </small>
                </div>

                <div class="col-12 text-center">
                  <button
                    type="button"
                    class="btn btn-danger me-2"
                    @click="volver()" >
                    <i class="bi bi-reply me-2"></i>Volver
                  </button>
                  <button
                    type="button"
                    class="btn btn-primary ms-2"
                    @click="guardar()" >
                    <i class="bi me-2"
                      :class="idCategoria ? 'bi-pencil' : 'bi-floppy'" ></i>
                    {{ idCategoria ? "Editar" : "Guardar" }}
                  </button>
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
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import { show, update, store } from "@/services/CategoriaServices";
import Swal from "sweetalert2";

const router = useRouter();
const idCategoria = router.currentRoute.value.params.idCategoria;
const errors = ref({});
const formulario = ref({
  nombre: "",
});

onMounted(() => {
  console.log("ID de la categoría desde la URL:", idCategoria.value);
  if (idCategoria) {
    verRegistro();
  }
});

const guardar = async () => {
  errors.value = {};

  try {
    const formData = new FormData();
    formData.append("nombre", formulario.value.nombre);

    if (idCategoria) {
      formData.append("_method", "PUT");
      await update(idCategoria, formData);

      Swal.fire({
        title: "Modificado",
        text: "Registro modificado.",
        icon: "success",
      });
    } else {
      await store(formData);
      Swal.fire({
        title: "Guardado",
        text: "Nuevo registro guardado",
        icon: "success",
      });
    }
    volver();
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    }
    console.error(error);
  }
};

const verRegistro = async () => {
  try {
    const { data } = await show(idCategoria);
    console.log("Datos recibidos:", data);

    if (data.datos) {
      formulario.value = {
        nombre: data.datos.nombre,
      };
    } else {
      console.warn("No se encontró la categoría.");
    }
  } catch (error) {
    console.error("Error al obtener el registro:", error);
  }
};

const volver = () => {
  router.push("/categorias");
};
</script>

