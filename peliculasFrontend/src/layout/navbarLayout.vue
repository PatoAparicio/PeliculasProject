<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg center bg-body-tertiary fixed-top  " data-bs-spy="scroll"  >
            <div class="container-fluid ">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item px-4">
                            <router-link class="nav-link" to="/peliculas"> Películas</router-link>
                        </li>
                        <li class="nav-item px-4">
                            <router-link class="nav-link" to="/categorias"> Categorías</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

</template>
<script>
import { RouterLink } from 'vue-router';

</script>