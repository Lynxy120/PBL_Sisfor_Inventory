/**
 * Form Validation Utilities - ENHANCED
 * Provides comprehensive client-side validation for forms
 * with real-time feedback and accessibility improvements
 */

/* ============ REQUIRED FIELD VALIDATION ============ */
function validateRequired(fieldId, fieldName) {
  const field = document.getElementById(fieldId);
  const value = field.value.trim();

  if (!value) {
    showFieldError(fieldId, `${fieldName} harus diisi`);
    showAlert(`${fieldName} harus diisi`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ NUMBER VALIDATION ============ */
function validateNumber(fieldId, fieldName, min = 0, max = null) {
  const field = document.getElementById(fieldId);
  const value = field.value.trim();

  if (!value) {
    showFieldError(fieldId, `${fieldName} harus berupa angka`);
    showAlert(`${fieldName} tidak boleh kosong`, "warning");
    return false;
  }

  const numValue = parseFloat(value);

  if (isNaN(numValue)) {
    showFieldError(fieldId, `${fieldName} harus berupa angka`);
    showAlert(`${fieldName} harus berupa angka`, "warning");
    return false;
  }

  if (numValue < min) {
    showFieldError(fieldId, `${fieldName} harus minimal ${min}`);
    showAlert(`${fieldName} harus minimal ${min}`, "warning");
    return false;
  }

  if (max !== null && numValue > max) {
    showFieldError(fieldId, `${fieldName} tidak boleh lebih dari ${max}`);
    showAlert(`${fieldName} tidak boleh lebih dari ${max}`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ EMAIL VALIDATION ============ */
function validateEmail(fieldId, fieldName) {
  const field = document.getElementById(fieldId);
  const email = field.value.trim();

  if (!email) {
    showFieldError(fieldId, `${fieldName} harus diisi`);
    return false;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailRegex.test(email)) {
    showFieldError(
      fieldId,
      `${fieldName} tidak valid (gunakan format: user@domain.com)`,
    );
    showAlert(`${fieldName} tidak valid`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ PHONE VALIDATION ============ */
function validatePhone(fieldId, fieldName) {
  const field = document.getElementById(fieldId);
  const phone = field.value.trim();

  if (!phone) {
    showFieldError(fieldId, `${fieldName} harus diisi`);
    return false;
  }

  // Accept Indonesian phone format: +62/0 followed by 9-12 digits
  const phoneRegex = /^(\+62|0)[0-9]{9,12}$/;

  if (!phoneRegex.test(phone)) {
    showFieldError(
      fieldId,
      `${fieldName} format tidak valid (contoh: 081234567890 atau +628123456789)`,
    );
    showAlert(`${fieldName} tidak valid`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ FILE VALIDATION ============ */
function validateFile(
  fieldId,
  fieldName,
  allowedTypes = ["image/jpeg", "image/png", "image/gif"],
  maxSizeInMB = 5,
) {
  const fileInput = document.getElementById(fieldId);
  const file = fileInput.files[0];

  if (!file) {
    return true; // File optional
  }

  // Validate file type
  if (!allowedTypes.includes(file.type)) {
    const typeNames = allowedTypes
      .map((type) => type.split("/")[1].toUpperCase())
      .join(", ");
    showFieldError(fieldId, `${fieldName} hanya boleh berupa ${typeNames}`);
    showAlert(`Format file tidak didukung. Gunakan: ${typeNames}`, "warning");
    return false;
  }

  // Validate file size
  const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
  if (file.size > maxSizeInBytes) {
    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
    showFieldError(
      fieldId,
      `${fieldName} terlalu besar (${sizeMB}MB, maksimal ${maxSizeInMB}MB)`,
    );
    showAlert(`File terlalu besar. Maksimal ${maxSizeInMB}MB`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ PASSWORD STRENGTH VALIDATION ============ */
function validatePasswordStrength(fieldId, minLength = 6) {
  const field = document.getElementById(fieldId);
  const password = field.value;

  if (!password) {
    showFieldError(fieldId, "Password harus diisi");
    return false;
  }

  if (password.length < minLength) {
    showFieldError(fieldId, `Password harus minimal ${minLength} karakter`);
    showAlert(`Password harus minimal ${minLength} karakter`, "warning");
    return false;
  }

  // Check for strength
  const hasUpper = /[A-Z]/.test(password);
  const hasLower = /[a-z]/.test(password);
  const hasNumber = /[0-9]/.test(password);
  const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  const strength = [hasUpper, hasLower, hasNumber, hasSpecial].filter(
    Boolean,
  ).length;

  if (strength < 2) {
    showFieldError(
      fieldId,
      "Password terlalu lemah. Gunakan kombinasi huruf besar, kecil, angka, dan simbol",
    );
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ PASSWORD MATCH VALIDATION ============ */
function validatePasswordMatch(passwordFieldId, confirmFieldId) {
  const password = document.getElementById(passwordFieldId).value;
  const confirm = document.getElementById(confirmFieldId).value;

  if (password !== confirm) {
    showFieldError(confirmFieldId, "Password tidak cocok");
    showAlert("Password tidak cocok", "warning");
    return false;
  }

  clearFieldError(confirmFieldId);
  return true;
}

/* ============ DATE RANGE VALIDATION ============ */
function validateDateRange(startFieldId, endFieldId) {
  const startField = document.getElementById(startFieldId);
  const endField = document.getElementById(endFieldId);

  const startDate = new Date(startField.value);
  const endDate = new Date(endField.value);

  if (startDate > endDate) {
    showFieldError(endFieldId, "Tanggal akhir harus setelah tanggal mulai");
    showAlert("Tanggal mulai harus sebelum tanggal akhir", "warning");
    return false;
  }

  clearFieldError(startFieldId);
  clearFieldError(endFieldId);
  return true;
}

/* ============ SELECT/DROPDOWN VALIDATION ============ */
function validateSelect(fieldId, fieldName) {
  const field = document.getElementById(fieldId);
  const value = field.value;

  if (!value) {
    showFieldError(fieldId, `${fieldName} harus dipilih`);
    showAlert(`${fieldName} harus dipilih`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ USERNAME VALIDATION ============ */
function validateUsername(fieldId, fieldName) {
  const field = document.getElementById(fieldId);
  const username = field.value.trim();

  if (!username) {
    showFieldError(fieldId, `${fieldName} harus diisi`);
    return false;
  }

  // Alphanumeric and underscore, 3-20 characters
  const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

  if (!usernameRegex.test(username)) {
    showFieldError(
      fieldId,
      `${fieldName} harus 3-20 karakter (huruf, angka, underscore)`,
    );
    showAlert(`${fieldName} format tidak valid`, "warning");
    return false;
  }

  clearFieldError(fieldId);
  return true;
}

/* ============ GENERIC FORM VALIDATION ============ */
/**
 * Validate multiple form fields at once
 * Usage: validateForm([
 *   {fieldId: 'name', fieldName: 'Nama', type: 'required'},
 *   {fieldId: 'email', fieldName: 'Email', type: 'email'},
 *   {fieldId: 'price', fieldName: 'Harga', type: 'number', min: 0}
 * ])
 */
function validateForm(rules) {
  let isValid = true;

  for (const rule of rules) {
    let fieldValid = true;

    switch (rule.type) {
      case "required":
        fieldValid = validateRequired(rule.fieldId, rule.fieldName);
        break;
      case "number":
        fieldValid = validateNumber(
          rule.fieldId,
          rule.fieldName,
          rule.min || 0,
          rule.max || null,
        );
        break;
      case "email":
        fieldValid = validateEmail(rule.fieldId, rule.fieldName);
        break;
      case "phone":
        fieldValid = validatePhone(rule.fieldId, rule.fieldName);
        break;
      case "file":
        fieldValid = validateFile(
          rule.fieldId,
          rule.fieldName,
          rule.allowedTypes,
          rule.maxSizeInMB,
        );
        break;
      case "select":
        fieldValid = validateSelect(rule.fieldId, rule.fieldName);
        break;
      case "username":
        fieldValid = validateUsername(rule.fieldId, rule.fieldName);
        break;
      case "password":
        fieldValid = validatePasswordStrength(
          rule.fieldId,
          rule.minLength || 6,
        );
        break;
      case "passwordMatch":
        fieldValid = validatePasswordMatch(
          rule.passwordFieldId,
          rule.confirmFieldId,
        );
        break;
      case "dateRange":
        fieldValid = validateDateRange(rule.startFieldId, rule.endFieldId);
        break;
    }

    if (!fieldValid) {
      isValid = false;
    }
  }

  return isValid;
}

/* ============ CLEAR FIELD ERROR ============ */
function clearFieldError(fieldId) {
  const field = document.getElementById(fieldId);
  if (!field) return;

  field.classList.remove("is-invalid");
  field.classList.add("is-valid");

  // Remove error message
  const feedback = field.parentNode.querySelector(".invalid-feedback");
  if (feedback) {
    feedback.remove();
  }
}

/* ============ CLEAR FORM ERRORS ============ */
function clearFormErrors(formId) {
  const form = document.getElementById(formId);
  if (!form) return;

  const inputs = form.querySelectorAll("input, select, textarea");
  inputs.forEach((input) => {
    clearFieldError(input.id);
    input.classList.remove("is-valid");
  });
}

/* ============ SHOW FIELD ERROR ============ */
function showFieldError(fieldId, errorMessage) {
  const field = document.getElementById(fieldId);
  if (!field) return;

  field.classList.add("is-invalid");
  field.classList.remove("is-valid");

  // Remove existing error message
  const existingFeedback = field.parentNode.querySelector(".invalid-feedback");
  if (existingFeedback) {
    existingFeedback.remove();
  }

  // Add new error message
  const feedback = document.createElement("div");
  feedback.className = "invalid-feedback d-block";
  feedback.textContent = errorMessage;
  field.parentNode.appendChild(feedback);
}

/* ============ REAL-TIME VALIDATION HELPER ============ */
/**
 * Enable real-time validation on a field
 * Usage: enableRealTimeValidation('fieldId', 'fieldName', 'type')
 */
function enableRealTimeValidation(fieldId, fieldName, validationType) {
  const field = document.getElementById(fieldId);
  if (!field) return;

  field.addEventListener("blur", () => {
    validateRealTime(fieldId, fieldName, validationType);
  });

  field.addEventListener("input", () => {
    if (field.classList.contains("is-invalid")) {
      validateRealTime(fieldId, fieldName, validationType);
    }
  });
}

function validateRealTime(fieldId, fieldName, validationType) {
  switch (validationType) {
    case "required":
      validateRequired(fieldId, fieldName);
      break;
    case "email":
      validateEmail(fieldId, fieldName);
      break;
    case "number":
      validateNumber(fieldId, fieldName, 0);
      break;
    case "phone":
      validatePhone(fieldId, fieldName);
      break;
    case "username":
      validateUsername(fieldId, fieldName);
      break;
  }
}
