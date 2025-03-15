import { http, httpAsset, API_BASE_URL } from "./Http";

export const indexCategoria = () => {
    return http().get(`${API_BASE_URL}categoria`);
};
export const  categoriaActiva = () => {
    return http().get(`${API_BASE_URL}categoria-activas`);
};
export const update = (id, formulario) => {
    return http().post(`${API_BASE_URL}categoria/${id}`, formulario);
};
export const store = formulario => {
    return http().post(`${API_BASE_URL}categoria`, formulario);
}
export const show = id => {
    return http().get(`${API_BASE_URL}categoria/${id}`);
}
export const changeStatus = id => {
    return http().delete(`${API_BASE_URL}categoria/${id}`);
};
