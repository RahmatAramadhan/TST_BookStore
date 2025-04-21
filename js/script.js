const API_URL = "api.php"; // URL utama API
const btnBook = document.getElementById("btn-book");
const btnLogin = document.getElementById("btn-login");
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
    .then((response) => response.json()) // Parse JSON langsung
    .then((data) => {
        console.log("Response Data:", data); 
        if (data.success) {
            return data.success; // Kembalikan data yang relevan
        } else {
            throw new Error(data.message || "Unexpected server response");
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
            btnLogin.textContent = "Logout";
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
    btnLogin.textContent = "Register";
    loadLogin(); // Kembali ke halaman login
}

function register(){
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        alert("Password do not match!");
        return;
    }

    sendRequest("/register","POST", {username, password}).then((data) => {
        if (data) {
            alert("Register berhasil!");
            loadLogin();
        }else{
            alert("Register gagal: " + data.message);
        }
    });
}


// Fungsi untuk memuat daftar buku
function loadBook() {
    sendRequest("/getBook", "GET").then((response) => {
        const content = document.getElementById("content");

        const wrapper = document.createElement("div");
        wrapper.classList.add("wrapper");

        // Buat dua kolom
        const column1 = document.createElement("div");
        const column2 = document.createElement("div");

        // Tambahkan kelas 'column' ke setiap kolom
        column1.classList.add("column");
        column2.classList.add("column");

        // Pastikan data tersedia
        if (response.status === "true" && response.data.length > 0) {
            response.data.forEach((book, index) => {
                // Buat elemen kartu untuk setiap buku

                const card = document.createElement("div");
                card.classList.add("card");
                card.innerHTML = `
                    <h3>${book.Judul}</h3>
                    <p><strong>Price:</strong> Rp${book.Harga.toLocaleString()}</p>
                    <button onclick="addTransaction(${book.id})">Tambah</button>
                `;

                // Tambahkan kartu ke salah satu kolom secara bergantian
                if (index % 2 === 0) {
                    column1.appendChild(card);
                } else {
                    column2.appendChild(card);
                }
            });
        } else {
            // Jika tidak ada data, tampilkan pesan
            content.innerHTML = "<p>Tidak ada buku tersedia.</p>";
        }

        // Tambahkan kedua kolom ke kontainer utama
        wrapper.appendChild(column1);
        wrapper.appendChild(column2);

        content.innerHTML=``;
        content.appendChild(wrapper);
    }).catch((error) => {
        console.error("Error fetching books:", error);
        const content = document.getElementById("content");
        content.innerHTML = "<p>Terjadi kesalahan saat memuat data buku.</p>";
    });
}


// Fungsi untuk menampilkan form login
function loadLogin() {
    const content = document.getElementById("content");
    content.innerHTML = `
        <div>
            <h2>Login</h2>
        </div>
        <div class="card" id="card-input">
            <input type="text" id="username" placeholder="Username" /><br /><br />
            <input type="password" id="password" placeholder="Password" /><br /><br />
            <button onclick="login()">Login</button>
        </div>`;
    
}

function loadRegister(){
    const content = document.getElementById("content");
    content.innerHTML = `
    <div>
        <h2>Register</h2>
    </div>
    <div class="card" id="card-input">
        <input type="text" id="username" placeholder="Username" /><br /><br />
        <input type="password" id="password" placeholder="Password" /><br /><br />
        <input type="password" id="confirmPassword" placeholder="Re-Password" /><br/> <br/>
        <button onClick="register()">Register</button>
    </div>
    `;
}

// Periksa apakah token tersedia di cookies
document.addEventListener("DOMContentLoaded", () => {
    const token = getCookie('authToken');

    btnBook.addEventListener("click", ()=>{
        loadBook();
    });

    btnLogin.addEventListener("click", () => {
        if (btnLogin.textContent == "Login") {
            btnLogin.textContent = "Register";
            loadLogin();
        }else if(btnLogin.textContent == "Register"){
            btnLogin.textContent = "Login";
            loadRegister();
        }else{
            logout();
        }
        
    });

    if (token) {
        btnLogin.textContent = "Logout";
        loadBook(); // Jika token ada, langsung memuat daftar buku
    } else {
        btnLogin.textContent = "Register";
        loadLogin(); // Jika tidak, tampilkan halaman login
    }
});
