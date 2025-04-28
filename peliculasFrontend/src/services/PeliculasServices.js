import { http, httpAsset, API_BASE_URL} from "./Http";

export const index = () => {
    return http().get(`${API_BASE_URL}pelicula`);
};
export const  peliculaActiva = () => {
    return http().get(`${API_BASE_URL}pelicula-activas`);
};
export const update = (id, formulario) => {
    return httpAsset().post(`${API_BASE_URL}pelicula/${id}`, formulario);
};
export const store = formulario => {
    return httpAsset().post(`${API_BASE_URL}pelicula`, formulario);
}
export const show = id => {
    return http().get(`${API_BASE_URL}pelicula/${id}`);
}
export const changeStatus = id => {
    return http().delete(`${API_BASE_URL}pelicula/${id}`);
};
