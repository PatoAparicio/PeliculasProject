import { createRouter, createWebHistory } from 'vue-router'
import PeliculasView from '@/views/crud/PeliculasView.vue'
import PeliculasFormView from '@/views/crud/PeliculasFormView.vue'
import CategoriasFormView from '@/views/crud/CategoriasFormView.vue'
import CategoriasView from '@/views/crud/CategoriasView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
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
      path: '/categorias-form/:idcategoria?',
      name: 'categoriasformcrud',
      component: CategoriasFormView,
    },

  ],
})

export default router
