import { createRouter, createWebHistory } from 'vue-router'
import PeliculasView from '@/views/crud/PeliculasView.vue'
import PeliculasFormView from '@/views/crud/PeliculasFormView.vue'
import CategoriasFormView from '@/views/crud/CategoriasFormView.vue'
import CategoriasView from '@/views/crud/CategoriasView.vue'
import HomeView from '@/views/HomeView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/peliculas',
      name: 'peliculascrud',
      component: PeliculasView,
    },
    {
      path: '/peliculas-form/:idPelicula?',
      name: 'peliculasformcrud',
      component: PeliculasFormView,
    },
    {
      path: '/categorias',
      name: 'categoriascrud',
      component: CategoriasView,
    },
    {
      path: '/categorias-form/:idCategoria?',
      name: 'categoriasformcrud',
      component: CategoriasFormView,
    },

  ],
})

export default router
