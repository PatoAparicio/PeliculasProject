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
                <i class="bi me-2" :class="idPelicula !== '' ? 'bi-pencil' : 'bi-floppy'"></i>
                {{ idPelicula !== "" ? "Editar Registro" : "Nuevo Registro" }}
              </h5>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card shadow-lg">
            <div class="card-body">
              <div class="row gy-3">
                <div class="col-12 col-md-6">
                  <label for="nombre" class="form-label font-weight-bolder text-sm">Título
                  <span class="text-danger fst-italic">*</span></label>
                  <input
                    type="text"
                    class="form-control border"
                    :class="{ 'border-danger': errors.nombre }"
                    placeholder="Escriba..."
                    id="nombre"
                    v-model="formulario.nombre"/>
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.nombre"
                    >{{ errors.nombre[0] }}</small
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label
                    for="descripcion"
                    class="form-label font-weight-bolder text-sm"
                    >Descripción<span class="text-danger fst-italic"
                      >*</span
                    ></label
                  >
                  <input
                    type="text"
                    class="form-control border"
                    :class="{ 'border-danger': errors.descripcion }"
                    placeholder="Escriba..."
                    id="descripcion"
                    v-model="formulario.descripcion"
                  />
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.descripcion"
                    >{{ errors.descripcion[0] }}</small
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label
                    for="precio"
                    class="form-label font-weight-bolder text-sm"
                    >Precio<span class="text-danger fst-italic">*</span></label
                  >
                  <input
                    type="number"
                    class="form-control border"
                    :class="{ 'border-danger': errors.precio }"
                    placeholder="Precio"
                    id="precio"
                    v-model="formulario.precio"
                  />
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.precio"
                    >{{ errors.precio[0] }}</small
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label
                    for="categoria"
                    class="form-label font-weight-bolder text-sm"
                    >Categoría<span class="text-danger fst-italic"
                      >*</span
                    ></label
                  >
                  <select
                    class="form-control border"
                    :class="{ 'border-danger': errors.categoria_id }"
                    id="categoria"
                    v-model="formulario.categoria"
                  >
                    <option value="" disabled>Seleccione una categoría</option>
                    <option
                      v-for="categoria in categorias"
                      :key="categoria.id"
                      :value="categoria.id"
                    >
                      {{ categoria.nombre }}
                    </option>
                  </select>
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.categoria_id"
                    >{{ errors.categoria_id[0] }}</small
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label
                    for="imagen"
                    class="form-label font-weight-bolder text-sm"
                    >Inserte imagen
                    <span
                      v-show="idPelicula == ''"
                      class="text-danger fst-italic"
                      >*</span
                    ></label
                  >
                  <input
                    type="file"
                    class="form-control border"
                    :class="{ 'border-danger': errors.imagen }"
                    id="imagen"
                    accept=".png, .jpg .jpeg"
                    @change="obtenerImagen($event)"
                  />
                  <small
                    class="text-danger fst-italic text-xs"
                    v-if="errors.imagen"
                    >{{ errors.imagen[0] }}</small
                  >
                </div>
                <div class="col-12 col-md-12 text-center" v-if="previewImagen">
                  <div class="card">
                    <div class="card-header">
                      <h5>Vista previa de la imagen</h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12 text-center">
                          <img
                            :src="previewImagen"
                            alt="preview_imagen"
                            width="60px"
                            height="60px"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 text-center">
                  <button
                    type="button"
                    class="btn btn-danger me-2"
                    @click="volver()"
                  >
                    <i class="bi bi-reply me-2"></i>Volver
                  </button>
                  <button
                    type="button"
                    class="btn btn-primary ms-2"
                    @click="guardar()"
                  >
                    <i
                      class="bi me-2"
                      :class="idPelicula !== '' ? 'bi-pencil' : 'bi-floppy'"
                    ></i
                    >{{ idPelicula != "" ? "Editar" : "Guardar" }}
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
import { show, update, store } from "@/services/PeliculasServices";
import { categoriaActiva } from "@/services/CategoriaServices";
import Swal from "sweetalert2";

const router = useRouter();
const idPelicula = router.currentRoute.value.params.idPelicula;
const errors = ref({});
const categorias = ref([]);

const formulario = ref({
  nombre: "",
  descripcion: "",
  precio: "",
  categoria: "",
  imagen: null,
});

const previewImagen = computed(() => {
  if (formulario.value.imagen instanceof File) {
    return URL.createObjectURL(formulario.value.imagen); 
  } else if (formulario.value.imagen) {
    return formulario.value.imagen; 
  }
  return null;
});

onMounted(() => {
  obtenerCategorias();
  if (idPelicula !== "") {
    verRegistro();
  }
});

const obtenerCategorias = async () => {
  try {
    const response = await categoriaActiva();
    categorias.value = response.data.datos;
  } catch (error) {
    console.error("Error al obtener categorías:", error);
  }
};

const obtenerImagen = (event) => {
  formulario.value.imagen = event.target.files[0];
};

const verRegistro = async () => {
  try {
    const { data } = await show(idPelicula);
    formulario.value = {
      nombre: data.datos.nombre,
      descripcion: data.datos.descripcion,
      precio: data.datos.precio,
      categoria: data.datos.categoria_id,
      imagen: data.datos.imagen,
    };
  } catch (error) {
    console.log(error);
  }
};

const guardar = async () => {
  errors.value = {};
  try {
    const formData = new FormData();
    formData.append("nombre", formulario.value.nombre);
    formData.append("descripcion", formulario.value.descripcion);
    formData.append("precio", formulario.value.precio);
    formData.append("categoria", formulario.value.categoria);

    if (formulario.value.imagen instanceof File) {
      formData.append("imagen", formulario.value.imagen); 
    }

    if (idPelicula !== "") {
      formData.append("_method", "PUT");
      await update(idPelicula, formData);
      Swal.fire("Modificado", "Registro modificado.", "success");
    } else {
      await store(formData);
      Swal.fire("Guardado", "Nuevo registro guardado", "success");
    }

    volver();
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    }
  }
};

const volver = () => {
  router.push("/peliculas");
};
</script>
