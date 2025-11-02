import axios from "axios";

const api = axios.create({
    baseURL:
        process.env.NODE_ENV === "development"
            ? "http://localhost:8000"
            : "https://hospital.xetroot.com",
    withCredentials: true,
    headers: { "X-Requested-With": "XMLHttpRequest" },
});

// For Laravel Sanctum SPA auth: ensure XSRF cookie is set before state-changing requests
let csrfInitialized = false;
async function ensureCsrfCookie() {
    if (csrfInitialized) return;
    try {
        await api.get("/sanctum/csrf-cookie", { withCredentials: true });
        csrfInitialized = true;
    } catch (e) {
        // noop: subsequent request will still attempt and surface error
    }
}

api.interceptors.request.use(async (config) => {
    const method = (config.method || "get").toLowerCase();
    if (["post", "put", "patch", "delete"].includes(method)) {
        await ensureCsrfCookie();
    }
    return config;
});

// Keep compatibility with meta csrf for web routes if present
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

export default api;
