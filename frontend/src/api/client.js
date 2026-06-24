// const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8000";
const API_URL = import.meta.env.VITE_API_URL || "";


function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);

    if (parts.length === 2) {
        return parts.pop().split(";").shift();
    }

    return null;
}

export async function csrf() {
    await fetch(`${API_URL}/sanctum/csrf-cookie`, {
        credentials: "include",
    });
}

export async function request(endpoint, options = {}) {
    const xsrfToken = getCookie("XSRF-TOKEN");

    const response = await fetch(`${API_URL}${endpoint}`, {
        credentials: "include",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            ...(xsrfToken
                ? {
                      "X-XSRF-TOKEN": decodeURIComponent(xsrfToken),
                  }
                : {}),
            ...options.headers,
        },
        ...options,
    });

    let data = {};

    try {
        data = await response.json();
    } catch {
        data = {};
    }

    if (!response.ok) {
        throw data;
    }

    return data.data ?? data;
}
