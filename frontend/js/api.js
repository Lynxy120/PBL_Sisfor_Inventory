/**
 * API Configuration and Helper Functions
 * For connecting Frontend to Backend API
 */

// Development configuration
const API_BASE_URL = "http://localhost:8001";

// Production/Subfolder configuration (commented out)
// const API_BASE_URL =
//   window.location.origin +
//   (window.location.pathname.includes("/umkm/") ? "/umkm" : "") +
//   "/backend/public";

// Storage keys
const STORAGE_KEYS = {
  USER_ID: "user_id",
  USER_NAME: "user_name",
  USER_ROLE: "user_role",
  TOKEN: "auth_token",
};

/**
 * Get authentication headers
 */
function getAuthHeaders() {
  const token = localStorage.getItem(STORAGE_KEYS.TOKEN);
  const userId = localStorage.getItem(STORAGE_KEYS.USER_ID);
  const userRole = localStorage.getItem(STORAGE_KEYS.USER_ROLE);

  const headers = {
    "Content-Type": "application/json",
  };

  // Backend expects these headers for authorization
  if (userId) headers["X-User-Id"] = userId;
  if (userRole) headers["X-User-Role"] = userRole;

  // Keep bearer token if backend adds token later (non-breaking)
  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  return headers;
}

/**
 * Generic API request function
 */
async function apiRequest(endpoint, method = "GET", data = null) {
  const options = {
    method: method,
    headers: getAuthHeaders(),
  };

  if (data && (method === "POST" || method === "PUT")) {
    options.body = JSON.stringify(data);
  }

  try {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
    let result;
    const responseClone = response.clone();
    try {
      result = await response.json();
    } catch (jsonErr) {
      const text = await responseClone.text();
      throw new Error(`Response is not valid JSON: ${text.substring(0, 200)}`);
    }
    if (!response.ok) {
      throw new Error(result.message || "Request failed");
    }
    return result;
  } catch (error) {
    console.error("API Error:", error);
    throw error;
  }
}

/**
 * API request with FormData (for file uploads)
 */
async function apiRequestFormData(endpoint, method = "POST", formData) {
  const token = localStorage.getItem(STORAGE_KEYS.TOKEN);
  const userId = localStorage.getItem(STORAGE_KEYS.USER_ID);
  const userRole = localStorage.getItem(STORAGE_KEYS.USER_ROLE);
  const headers = {};

  // Backend expects these headers for authorization
  if (userId) headers["X-User-Id"] = userId;
  if (userRole) headers["X-User-Role"] = userRole;

  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  try {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      method: method,
      headers: headers,
      body: formData,
    });
    let result;
    const responseClone = response.clone();
    try {
      result = await response.json();
    } catch (jsonErr) {
      const text = await responseClone.text();
      throw new Error(`Response is not valid JSON: ${text.substring(0, 200)}`);
    }
    if (!response.ok) {
      throw new Error(result.message || "Request failed");
    }
    return result;
  } catch (error) {
    console.error("API Error:", error);
    throw error;
  }
}

// ============ Authentication ============

/**
 * Login user
 */
async function login(username, password) {
  const response = await apiRequest("/login", "POST", { username, password });

  if (response.status === "success" && response.data) {
    localStorage.setItem(STORAGE_KEYS.USER_ID, response.data.id);
    localStorage.setItem(STORAGE_KEYS.USER_NAME, response.data.nama);
    localStorage.setItem(STORAGE_KEYS.USER_ROLE, response.data.role);
    if (response.data.token) {
      localStorage.setItem(STORAGE_KEYS.TOKEN, response.data.token);
    }
  }

  return response;
}

/**
 * Logout user
 */
function logout() {
  localStorage.removeItem(STORAGE_KEYS.USER_ID);
  localStorage.removeItem(STORAGE_KEYS.USER_NAME);
  localStorage.removeItem(STORAGE_KEYS.USER_ROLE);
  localStorage.removeItem(STORAGE_KEYS.TOKEN);

  // Clear session on server
  fetch("includes/sync-session.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "logout=true",
  })
    .then(() => {
      window.location.href = "login.php";
    })
    .catch(() => {
      window.location.href = "login.php";
    });
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
  return localStorage.getItem(STORAGE_KEYS.USER_ID) !== null;
}

/**
 * Get current user info
 */
function getCurrentUser() {
  return {
    id: localStorage.getItem(STORAGE_KEYS.USER_ID),
    name: localStorage.getItem(STORAGE_KEYS.USER_NAME),
    role: localStorage.getItem(STORAGE_KEYS.USER_ROLE),
  };
}

// ============ Categories API ============

async function getCategories() {
  return await apiRequest("/categories");
}

async function getCategory(id) {
  return await apiRequest(`/categories/${id}`);
}

async function createCategory(data) {
  return await apiRequest("/categories", "POST", data);
}

async function updateCategory(id, data) {
  return await apiRequest(`/categories/${id}`, "PUT", data);
}

async function deleteCategory(id) {
  return await apiRequest(`/categories/${id}`, "DELETE");
}

// ============ Suppliers API ============

async function getSuppliers() {
  return await apiRequest("/suppliers");
}

async function getSupplier(id) {
  return await apiRequest(`/suppliers/${id}`);
}

async function createSupplier(data) {
  return await apiRequest("/suppliers", "POST", data);
}

async function updateSupplier(id, data) {
  return await apiRequest(`/suppliers/${id}`, "PUT", data);
}

async function deleteSupplier(id) {
  return await apiRequest(`/suppliers/${id}`, "DELETE");
}

// ============ Users API ============

async function getUsers() {
  return await apiRequest("/users");
}

async function getUser(id) {
  return await apiRequest(`/users/${id}`);
}

async function createUser(data) {
  return await apiRequest("/users", "POST", data);
}

async function updateUser(id, data) {
  return await apiRequest(`/users/${id}`, "PUT", data);
}

async function deleteUser(id) {
  return await apiRequest(`/users/${id}`, "DELETE");
}

// ============ Items API ============

async function getItems() {
  return await apiRequest("/items");
}

async function getItem(id) {
  return await apiRequest(`/items/${id}`);
}

async function createItem(formData) {
  return await apiRequestFormData("/items", "POST", formData);
}

async function updateItem(id, formData) {
  return await apiRequestFormData(`/items/${id}`, "PUT", formData);
}

async function deleteItem(id) {
  return await apiRequest(`/items/${id}`, "DELETE");
}

// ============ Stock Management API ============

async function stockIn(data) {
  return await apiRequest("/stock-in", "POST", data);
}

async function stockOut(data) {
  return await apiRequest("/stock-out", "POST", data);
}

async function stockAdjust(data) {
  return await apiRequest("/stock-adjust", "POST", data);
}

// ============ Stock History API ============

async function getStockHistories() {
  return await apiRequest("/stock-histories");
}

async function getStockHistoryByDateRange(start, end) {
  return await apiRequest("/stock-histories/date-range", "POST", {
    start,
    end,
  });
}

async function getItemStockHistory(itemId) {
  return await apiRequest(`/items/${itemId}/stock-history`);
}

// ============ Reports API ============

async function getSalesReport(start, end) {
  return await apiRequest("/reports/sales", "POST", { start, end });
}

async function getStockReport() {
  return await apiRequest("/reports/stock");
}

async function getLowStockReport(threshold = 10) {
  return await apiRequest("/reports/low-stock", "POST", { threshold });
}

async function getTopSellingReport(start, end, limit = 10) {
  return await apiRequest("/reports/top-selling", "POST", {
    start,
    end,
    limit,
  });
}

/**
 * Format currency to Indonesian Rupiah
 */
function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount);
}

/**
 * Format date to Indonesian locale
 */
function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("id-ID", options);
}

/**
 * Format datetime to Indonesian locale
 */
function formatDateTime(dateString) {
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return new Date(dateString).toLocaleDateString("id-ID", options);
}

/**
 * Enhanced Alert Message - with auto-dismiss
 */
function showAlert(message, type = "success", duration = 5000) {
  const alertDiv = document.createElement("div");
  const alertClass = `alert-${type}`;
  const bgClass = getAlertBackground(type);

  alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
  alertDiv.setAttribute("role", "alert");
  alertDiv.style.cssText = `
    top: 80px; 
    right: 20px; 
    z-index: 9999; 
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    animation: slideIn 0.3s ease forwards;
  `;

  const icon = getAlertIcon(type);

  alertDiv.innerHTML = `
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 1.3rem;">${icon}</span>
      <span style="flex: 1;">${message}</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;

  document.body.appendChild(alertDiv);

  setTimeout(() => {
    alertDiv.classList.remove("show");
    setTimeout(() => alertDiv.remove(), 300);
  }, duration);
}

/**
 * Get alert background color
 */
function getAlertBackground(type) {
  const backgrounds = {
    success: "#d1e7dd",
    warning: "#fff3cd",
    danger: "#f8d7da",
    info: "#cfe2ff",
  };
  return backgrounds[type] || backgrounds["info"];
}

/**
 * Get alert icon
 */
function getAlertIcon(type) {
  const icons = {
    success: "✓",
    warning: "⚠",
    danger: "✕",
    info: "ℹ",
  };
  return icons[type] || icons["info"];
}

/**
 * Show loading indicator on a button
 */
function setButtonLoading(buttonId, isLoading = true) {
  const button = document.getElementById(buttonId);
  if (!button) return;

  if (isLoading) {
    button.disabled = true;
    button.classList.add("loading");
    button.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
      <span>Memproses...</span>
    `;
  } else {
    button.disabled = false;
    button.classList.remove("loading");
    // Restore original text - should be set before calling this
  }
}

/**
 * Reset button to original state
 */
function resetButton(buttonId, originalText) {
  const button = document.getElementById(buttonId);
  if (!button) return;

  button.disabled = false;
  button.classList.remove("loading");
  button.innerHTML = originalText;
}

/**
 * Show API error details
 */
function handleApiError(error, context = "") {
  console.error("API Error Details:", error);

  let errorMessage = "Terjadi kesalahan saat memproses permintaan";

  if (error.message) {
    errorMessage = error.message;
  } else if (error.response?.message) {
    errorMessage = error.response.message;
  }

  if (context) {
    errorMessage = `${context}: ${errorMessage}`;
  }

  showAlert(errorMessage, "danger");
  return errorMessage;
}

/**
 * Confirm dialog with better styling
 */
function confirmDialog(message, title = "Konfirmasi") {
  return confirm(`${title}\n\n${message}`);
}

/**
 * Show success message with action
 */
function showSuccess(message, actionText = "Tutup") {
  showAlert(`<strong>Berhasil!</strong> ${message}`, "success", 4000);
}

/**
 * Show error message with action
 */
function showError(message) {
  showAlert(`<strong>Error!</strong> ${message}`, "danger", 5000);
}

/**
 * Show warning message
 */
function showWarning(message) {
  showAlert(`<strong>Peringatan!</strong> ${message}`, "warning", 5000);
}

/**
 * Show info message
 */
function showInfo(message) {
  showAlert(`<strong>Informasi:</strong> ${message}`, "info", 4000);
}
