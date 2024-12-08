const API_URL = "http://localhost/Buku/TokoBuku/api.php"; // URL utama API

// Fungsi untuk menyimpan token ke cookies
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = `${name}=${value};${expires};path=/`;
}

// Fungsi untuk mendapatkan token dari cookies
function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith(`${name}=`)) {
            return cookie.substring(name.length + 1);
        }
    }
    return null;
}

// Fungsi untuk menghapus cookie (misalnya, saat logout)
function deleteCookie(name) {
    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
}

// Fungsi untuk mengirim permintaan ke API
function sendRequest(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
    };

    const token = getCookie('authToken'); // Ambil token dari cookies
    if (token) {
        options.headers["Authorization"] = `Bearer ${token}`;
    }

    if (data) {
        options.body = JSON.stringify(data);
    }

    return fetch(`${API_URL}${endpoint}`, options)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parse JSON respons
        })
        .then((result) => {
            console.log("Response Data:", result); // Debug respons

            // Pastikan respons memiliki format yang sesuai
            if (result.status === "success" && result.data) {
                return result.data; // Kembalikan data buku
            } else {
                throw new Error(result.message || "Unexpected server response");
            }
        })
        .catch((error) => {
            console.error("Request error:", error.message || error);
            return { status: "error", message: "Request failed" };
        });
}


// Fungsi untuk login
function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    sendRequest("/login", "POST", { username, password }).then((data) => {
        if (data.status === 'true') {
            setCookie('authToken', data.token, 1); // Simpan token ke cookies (berlaku 1 hari)
            alert("Login berhasil!");
            loadBook(); // Memuat daftar buku setelah login
        } else {
            alert("Login gagal: " + data.message);
        }
    });
}

// Fungsi untuk logout
function logout() {
    deleteCookie('authToken'); // Hapus token dari cookies
    alert("Logout berhasil!");
    loadLogin(); // Kembali ke halaman login
}

// Fungsi untuk memuat daftar buku
function loadBook() {
    const endpoint = "/getBook";

    sendRequest(endpoint, "GET")
        .then((result) => {
            if (result.status === "success" && Array.isArray(result.data)) {
                const bookContainer = document.getElementById("book-container"); // Pastikan ID-nya sesuai
                bookContainer.innerHTML = ""; // Kosongkan kontainer sebelum menambahkan buku baru

                // Iterasi data buku dan tambahkan ke DOM
                result.data.forEach((book) => {
                    const bookItem = document.createElement("div");
                    bookItem.className = "book-item"; // Gunakan class untuk styling
                    bookItem.innerHTML = `
                        <h3>${book.Judul}</h3>
                        <p>Harga: Rp ${book.Harga.toLocaleString("id-ID")}</p>
                        <button onclick="buyBook(${book.id})">Beli</button>
                    `;
                    bookContainer.appendChild(bookItem);
                });
            } else {
                console.error("Failed to load books:", result.message);
                alert("Gagal memuat data buku. Coba lagi nanti.");
            }
        })
        .catch((error) => {
            console.error("Error while fetching books:", error);
            alert("Terjadi kesalahan saat mengambil data buku.");
        });
}

// Fungsi untuk menampilkan form login
function loadLogin() {
    const content = document.getElementById("content");
    content.innerHTML = `
        <h2>Login</h2>
        <div class="card">
            <input type="text" id="username" placeholder="Username" /><br /><br />
            <input type="password" id="password" placeholder="Password" /><br /><br />
            <button onclick="login()">Login</button>
        </div>`;
}

// Periksa apakah token tersedia di cookies
document.addEventListener("DOMContentLoaded", () => {
    const token = getCookie('authToken');
    if (token) {
        loadBook(); // Jika token ada, langsung memuat daftar buku
    } else {
        loadLogin(); // Jika tidak, tampilkan halaman login
    }
});
