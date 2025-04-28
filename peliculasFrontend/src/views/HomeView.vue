<template>
  <header>
    <navbarLayout />
  </header>
    <main>
      <div class="container">
      <div class="row gy-3 text-center">
        <div class="col-12">
          <h5 class="titulo-especial">
            <i class="bi bi-film"></i> PELÍCULAS
          </h5>
        </div>
      </div>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        <div v-if="datos.length === 0">
          <p>No existen películas disponibles</p> 
        </div>
        <div class="col" v-for="(dato, indice) in datos" :key="indice">
          <div class="card h-100 shadow">
            <img :src="`${ASSET_BASE_URL}${dato.imagen}`" class="card-img-top" alt="...">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-center">{{ dato.nombre }}</h5>
              <p> $ {{ dato.precio }} </p>
              <p>{{ dato.categoria.nombre }}</p>
              <p class="card-text flex-grow-1"> 
                {{ truncarDescripción(dato.descripcion, 50) }}...
              </p>
              <button class="btn btn-primary mt-auto" @click="verDetalle(dato.id)">Ver más</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-primary-subtle">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">
                Detalle Película
              </h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
              <div class="card border border-5">
                <img :src="`${ASSET_BASE_URL}${dato.imagen}`" :alt="dato.imagen" class="card-img-top" />
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h3 class="card-title">{{ dato.nombre }}</h3>
                      <p>{{ dato.categoria?.nombre }}</p>
                      <p class="card-text">{{ dato.descripcion }}</p>
                      <h4 class="card-text">Precio: {{ dato.precio }}</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                Cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer>
    <footerLayout />
    </footer>
   
</template>
<script setup>
import footerLayout from '@/layout/footerLayout.vue';
import navbarLayout from '@/layout/navbarLayout.vue';
import { onMounted, ref } from "vue";
import { peliculaActiva, show } from "@/services/PeliculasServices";
import { ASSET_BASE_URL } from "@/services/Http";
import { Modal } from "bootstrap/dist/js/bootstrap.bundle";
const datos = ref([]);
const dato = ref({});
let modal = null;
let instanciaModal = null;
onMounted(() => {
  listar();
  modal = document.getElementById("staticBackdrop");
  instanciaModal = new Modal(modal);
});
const listar = async () => {
  try {
    const { data } = await peliculaActiva();
    datos.value = data.datos;
    console.log(datos.value);
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
const verDetalle = async (param) => {
  try {
    const { data } = await show(param);
    dato.value = data.datos;
    instanciaModal.show();
  } catch (error) {
    //console.log(error);
  }
};
</script>