const API_URL = import.meta.env.VITE_API_URL || "http://localhost:4000/api";

export function getToken() {
  return localStorage.getItem("gcm_token");
}

export function setSession(session) {
  localStorage.setItem("gcm_token", session.token);
  localStorage.setItem("gcm_user", JSON.stringify(session.user));
}

export function clearSession() {
  localStorage.removeItem("gcm_token");
  localStorage.removeItem("gcm_user");
}

export function getStoredUser() {
  const raw = localStorage.getItem("gcm_user");
  return raw ? JSON.parse(raw) : null;
}

export async function api(path, options = {}) {
  const token = getToken();
  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  });

  const payload = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(payload.message || "Error de comunicacion con la API");
  }

  return payload;
}

export async function downloadApi(path, options = {}) {
  const token = getToken();
  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  });

  if (!response.ok) {
    const payload = await response.json().catch(() => ({}));
    throw new Error(payload.message || "Error descargando archivo");
  }

  return {
    blob: await response.blob(),
    filename: response.headers
      .get("Content-Disposition")
      ?.match(/filename="(.+)"/)?.[1],
  };
}

export const authApi = {
  login: (credentials) =>
    api("/auth/login", {
      method: "POST",
      body: JSON.stringify(credentials),
    }),
  me: () => api("/auth/me"),
};

export const quotesApi = {
  list: () => api("/quotes"),
  get: (id) => api(`/quotes/${id}`),
  create: (quote) =>
    api("/quotes", {
      method: "POST",
      body: JSON.stringify(quote),
    }),
  update: (id, quote) =>
    api(`/quotes/${id}`, {
      method: "PUT",
      body: JSON.stringify(quote),
    }),
  remove: (id) =>
    api(`/quotes/${id}`, {
      method: "DELETE",
    }),
  exportPdf: (quote) =>
    downloadApi("/quotes/export-pdf", {
      method: "POST",
      body: JSON.stringify(quote),
    }),
};

export const dashboardApi = {
  summary: () => api("/dashboard/summary"),
};
