/*!
 * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
 */
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {
  // Toggle the side navigation
  const sidebarToggle = document.body.querySelector("#sidebarToggle");
  if (sidebarToggle) {
    // Persist sidebar toggle between refreshes
    if (localStorage.getItem("sb|sidebar-toggle") === "true") {
      document.body.classList.add("sb-sidenav-toggled");
    }

    sidebarToggle.addEventListener("click", (event) => {
      event.preventDefault();
      document.body.classList.toggle("sb-sidenav-toggled");
      localStorage.setItem(
        "sb|sidebar-toggle",
        document.body.classList.contains("sb-sidenav-toggled"),
      );
    });
  }
});

// nilai target
const percent = 75.55;

// ambil elemen lingkaran
const circle = document.querySelector(".ring");
const radius = 75;
const circumference = 2 * Math.PI * radius;

circle.style.strokeDasharray = `${circumference}`;

// hitung progress
const offset = circumference - (percent / 100) * circumference;
circle.style.strokeDashoffset = offset;

// tampilkan angka
document.getElementById("percent").innerText = percent + "%";

// TOGGLE EDIT VIEW
function toggleEdit(section) {
  document.getElementById("view-" + section).classList.toggle("d-none");
  document.getElementById("edit-" + section).classList.toggle("d-none");
}

// PERSONAL
function savePersonal() {
  v_fname.innerText = fname.value;
  v_lname.innerText = lname.value;
  v_email.innerText = email.value;
  v_phone.innerText = phone.value;
  v_bio.innerText = bio.value;
  toggleEdit("personal");
}

// ADDRESS
function saveAddress() {
  v_country.innerText = country.value;
  v_city.innerText = city.value;
  v_postal.innerText = postal.value;
  v_tax.innerText = tax.value;
  toggleEdit("address");
}

function saveChanges() {
  const data = {
    first_name: document.getElementById("fname").value,
    last_name: document.getElementById("lname").value,
    email: document.getElementById("email").value,
    phone: document.getElementById("phone").value,
    bio: document.getElementById("bio").value,
  };

  console.log("Data dikirim:", data);

  // 🔗 kalau mau sambung ke API
  /*
  fetch("http://localhost/api/update_profile.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(res => {
    alert("Profile berhasil diupdate!");
  });
  */

  alert("✅ Perubahan berhasil disimpan");

  // tutup modal
  const modalEl = document.getElementById("editProfileModal");
  const modal = bootstrap.Modal.getInstance(modalEl);
  modal.hide();
}

const radios = document.querySelectorAll("input[name='tipe']");

const formMasuk = document.getElementById("form-masuk");
const formKeluar = document.getElementById("form-keluar");
const formPenyesuaian = document.getElementById("form-penyesuaian");

radios.forEach((radio) => {
  radio.addEventListener("change", function () {
    const tipe = this.value;

    // sembunyikan semua dulu
    formMasuk.classList.add("d-none");
    formKeluar.classList.add("d-none");
    formPenyesuaian.classList.add("d-none");

    // tampilkan sesuai pilihan
    if (tipe === "Masuk") {
      formMasuk.classList.remove("d-none");
    } else if (tipe === "Keluar") {
      formKeluar.classList.remove("d-none");
    } else if (tipe === "Penyesuaian") {
      formPenyesuaian.classList.remove("d-none");
    }
  });
});

async function loadHistory() {
  const tbody = document.getElementById("historyTableBody");
  const start = document.getElementById("startDate").value;
  const end = document.getElementById("endDate").value;

  try {
    const result = await apiCall("/stock-histories/date-range", "POST", {
      start,
      end,
    });
    tbody.innerHTML = "";

    if (result.data.length === 0) {
      tbody.innerHTML =
        '<tr><td colspan="6" class="text-center py-4">Tidak ada riwayat untuk periode ini.</td></tr>';
      return;
    }

    result.data.forEach((h) => {
      const typeBadge =
        {
          in: "bg-success",
          out: "bg-danger",
          adjustment: "bg-warning text-dark",
        }[h.tipe] || "bg-secondary";

      const sourceText =
        h.sumber === "supplier"
          ? h.nama_suppliers || "Supplier"
          : h.sumber === "sales"
            ? "Penjualan"
            : h.sumber;

      tbody.innerHTML += `
                    <tr>
                        <td class="ps-4 small">
                            ${formatDateTime(h.tanggal)}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${h.nama_barang}</div>
                            <small class="text-muted">ID: #${h.item_id}</small>
                        </td>
                        <td><span class="badge ${typeBadge}">${h.tipe.toUpperCase()}</span></td>
                        <td>
                            <div class="fw-bold ${h.tipe === "in" ? "text-success" : h.tipe === "out" ? "text-danger" : "text-primary"}">
                                ${h.tipe === "in" ? "+" : h.tipe === "out" ? "-" : ""}${h.jumlah}
                            </div>
                        </td>
                        <td>
                            <div class="small">${sourceText}</div>
                        </td>
                        <td>
                            <small class="text-muted">${h.keterangan || "-"}</small>
                        </td>
                    </tr>
                `;
    });
  } catch (error) {
    tbody.innerHTML = (
      <tr>
        <td colspan="6" class="text-center text-danger">
          Gagal memuat riwayat.
        </td>
      </tr>
    );
  }
}

let items = [];

async function init() {
  try {
    // Load Items
    const itemRes = await apiCall("/items");
    items = itemRes.data;
    const itemSelect = document.getElementById("itemSelect");
    items.forEach((i) => {
      itemSelect.innerHTML += (
        <option value="${i.id}">
          ${i.nama_barang} (Stok: ${i.stok})
        </option>
      );
    });

    // Load Suppliers
    const supRes = await apiCall("/suppliers");
    const supSelect = document.getElementById("supplierSelect");
    supRes.data.forEach((s) => {
      supSelect.innerHTML += (
        <option value="${s.id}">${s.nama_suppliers}</option>
      );
    });

    loadRecent();
  } catch (error) {
    console.error("Init Error:", error);
  }
}

document.getElementById("itemSelect").addEventListener("change", (e) => {
  const item = items.find((i) => i.id == e.target.value);
  const detail = document.getElementById("itemDetail");
  if (item) {
    document.getElementById("currentStock").textContent = item.stok;
    detail.classList.remove("d-none");
  } else {
    detail.classList.add("d-none");
  }
});

// Handle Radio Changes
document.getElementsByName("type").forEach((radio) => {
  radio.addEventListener("change", (e) => {
    const supplierGroup = document.getElementById("supplierGroup");
    const jumlahLabel = document.getElementById("jumlahLabel");

    if (e.target.value === "in") {
      supplierGroup.classList.remove("d-none");
      jumlahLabel.textContent = "Jumlah Masuk";
    } else if (e.target.value === "out") {
      supplierGroup.classList.add("d-none");
      jumlahLabel.textContent = "Jumlah Keluar";
    } else {
      supplierGroup.classList.add("d-none");
      jumlahLabel.textContent = "Set Stok Baru Menjadi";
    }
  });
});

// Handle Form Submission
document.addEventListener("DOMContentLoaded", function () {
  const radios = document.querySelectorAll('input[name="tipe"]');
  const formMasuk = document.getElementById("form-masuk");
  const formKeluar = document.getElementById("form-keluar");
  const formPenyesuaian = document.getElementById("form-penyesuaian");

  function hideAll() {
    formMasuk.classList.remove("active");
    formKeluar.classList.remove("active");
    formPenyesuaian.classList.remove("active");
  }

  function showForm(value) {
    hideAll();

    if (value === "Masuk") {
      formMasuk.classList.add("active");
    } else if (value === "Keluar") {
      formKeluar.classList.add("active");
    } else if (value === "Penyesuaian") {
      formPenyesuaian.classList.add("active");
    }
  }

  // default saat load (Stok Masuk)
  const checked = (_attach = document.querySelector(
    'input[name="tipe"]:checked',
  ));
  if (checked) showForm(checked.value);

  // saat radio diklik
  radios.forEach((radio) => {
    radio.addEventListener("change", function () {
      showForm(this.value);
    });
  });
});
