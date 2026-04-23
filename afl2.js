function convertCurrency() {
  var rupiah = document.getElementById("rupiah").value;
  var usd = document.getElementById("usd").value;
  document.getElementById("usdOutput").innerHTML = usd;

  var hasil = rupiah * usd;

  document.getElementById("hasil").innerHTML = hasil;
}

function tampilkanSalam() {
  var sekarang = new Date();
  var jam = sekarang.getHours();
  var salam = "";

  if (jam >= 3 && jam < 9) {
    salam = "Selamat pagi";
  } else if (jam >= 9 && jam < 15) {
    salam = "Selamat siang";
  } else if (jam >= 15 && jam < 19) {
    salam = "Selamat petang";
  } else {
    salam = "Selamat malam";
  }

  alert(salam);

  var hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  var bulan = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  var tanggal =
    hari[sekarang.getDay()] +
    ", " +
    sekarang.getDate() +
    " " +
    bulan[sekarang.getMonth()] +
    " " +
    sekarang.getFullYear();

  alert(tanggal);
}

window.onload = tampilkanSalam;
