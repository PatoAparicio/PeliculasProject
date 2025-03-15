import axios from 'axios';

export const API_BASE_URL = 'http://127.0.0.1:8000/api/'; 
export const ASSET_BASE_URL = 'http://127.0.0.1:8000/'; 

export const http=()=> {
    const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
    });
    return api;
}
export const httpAsset=()=> {
    const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        Accept: "application/json",
		"Content-Type": "multipart/form-data",
    },
    });
    return api;
}
