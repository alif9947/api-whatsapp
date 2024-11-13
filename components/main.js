$("#login-button").click(function (event) {
  event.preventDefault();

  $("form").fadeOut(500);
  $(".wrapper").addClass("form-success");
});

// main.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("absensiForm");
  const responseDiv = document.getElementById("response");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Mencegah halaman reload saat submit form

    // Ambil data dari form
    const formData = new FormData(form);

    // Kirim data menggunakan fetch API
    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((result) => {
        // Tampilkan respon dari server di div#response
        responseDiv.textContent = result; // Menampilkan pesan sukses atau error

        // Menambahkan gaya dinamis tergantung hasilnya
        if (result.includes("berhasil")) {
          responseDiv.style.color = "green"; // Warna hijau untuk pesan sukses
        } else {
          responseDiv.style.color = "red"; // Warna merah untuk pesan error
        }

        form.reset(); // Mengosongkan form setelah submit
      })
      .catch((error) => {
        console.error("Error:", error);
        responseDiv.textContent = "Terjadi kesalahan. Silakan coba lagi.";
        responseDiv.style.color = "red";
      });
  });
});
