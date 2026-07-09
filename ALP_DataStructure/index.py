import heapq
from datetime import date

DUMMY_PRODUKSI = [
    ("P001", "Jaket Denim", 100, "2026-01-01", "High", "Menunggu Produksi", "2026-01-05"),
    ("P002", "Celana Denim", 80, "2026-01-02", "Medium", "Menunggu Produksi", "2026-01-06"),
    ("P003", "Kemeja Denim", 60, "2026-01-03", "Low", "Menunggu Produksi", "2026-01-07"),
    ("P004", "Rok Denim", 50, "2026-01-04", "High", "Menunggu Produksi", "2026-01-08"),
    ("P005", "Overall Denim", 40, "2026-01-05", "Medium", "Menunggu Produksi", "2026-01-09"),
    ("P006", "Short Denim", 120, "2026-01-06", "Low", "Menunggu Produksi", "2026-01-10"),
    ("P007", "Hoodie Denim", 30, "2026-01-07", "High", "Selesai", "2026-01-11"),
    ("P008", "Vest Denim", 70, "2026-01-08", "Medium", "Selesai", "2026-01-12"),
    ("P009", "Cargo Denim", 90, "2026-01-09", "Low", "Selesai", "2026-01-13"),
    ("P010", "Wide Leg Denim", 55, "2026-01-10", "Medium", "Selesai", "2026-01-14"),
]

# ==========================
# CLASS PRODUKSI
# ==========================
class Produksi:
    PRIORITAS_MAP = {
        "high": 1,
        "medium": 2,
        "low": 3
    }

    def __init__(
        self,
        idProduksi,
        modelProduk,
        jumlahProduksi,
        tanggalTerimaProduksi,
        prioritasProduksi,
        statusProduksi="Menunggu Produksi",
        tanggalMulaiProduksi="",
    ):
        self.idProduksi = idProduksi
        self.modelProduk = modelProduk
        self.jumlahProduksi = jumlahProduksi
        self.tanggalTerimaProduksi = tanggalTerimaProduksi
        self.prioritasProduksi = prioritasProduksi
        self.statusProduksi = statusProduksi
        self.tanggalMulaiProduksi = tanggalMulaiProduksi
        self.prioritas = self._prioritas_level(prioritasProduksi)

    def _prioritas_level(self, prioritasProduksi):
        if isinstance(prioritasProduksi, str):
            return self.PRIORITAS_MAP.get(prioritasProduksi.strip().lower(), 3)
        if isinstance(prioritasProduksi, int):
            return prioritasProduksi
        return 3

    # Mengembalikan data
    def getData(self):
        return {
            "ID Produksi": self.idProduksi,
            "Model Produk": self.modelProduk,
            "Jumlah Produksi": self.jumlahProduksi,
            "Tanggal Terima Produksi": self.tanggalTerimaProduksi,
            "Prioritas Produksi": self.prioritasProduksi,
            "Status Produksi": self.statusProduksi,
            "Tanggal Mulai Produksi": self.tanggalMulaiProduksi
        }

    # Mengubah status
    def setStatus(self, statusBaru):
        self.statusProduksi = statusBaru

    # Menampilkan data
    def tampilData(self):
        print("\n===== DATA PRODUKSI =====")
        print(f"ID Produksi             : {self.idProduksi}")
        print(f"Model Produk            : {self.modelProduk}")
        print(f"Jumlah Produksi         : {self.jumlahProduksi}")
        print(f"Tanggal Terima Produksi : {self.tanggalTerimaProduksi}")
        print(f"Prioritas Produksi      : {self.prioritasProduksi}")
        print(f"Status Produksi         : {self.statusProduksi}")
        print(f"Tanggal Mulai Produksi  : {self.tanggalMulaiProduksi}")
        print("==========================")

    # Digunakan oleh Priority Queue (Mengurutkan berdasarkan prioritas terkecil / High)
    def __lt__(self, other):
        return self.prioritas < other.prioritas


# ==========================
# CLASS ADMIN PRODUKSI
# ==========================
class AdminProduksi:
    def __init__(self):
        # HashMap
        self.dataProduksi = {}

        # Priority Queue
        self.antreanProduksi = []

        # ArrayList
        self.riwayatProduksi = []

        for id, model, jumlah, tanggalTerima, prioritas, status, tanggalMulai in DUMMY_PRODUKSI:
            produksi = Produksi(
                id,
                model,
                jumlah,
                tanggalTerima,
                prioritas,
                status,
                tanggalMulai
            )

            # Simpan ke HashMap
            self.dataProduksi[id] = produksi

            # Semua data masuk ke Riwayat
            self.riwayatProduksi.append(produksi)

            # Hanya yang masih menunggu masuk antrean
            if status == "Menunggu Produksi":
                heapq.heappush(self.antreanProduksi, produksi)

      

    def generateId(self):
            # Jika belum ada data sama sekali, mulai dari P001
            if not self.dataProduksi:
                return "P001"
            
            # Loop semua key di dictionary (contoh: 'P001', 'P010')
            # Potong huruf 'P' menggunakan slicing [1:], sisanya diubah ke integer
            # Lalu cari angka yang paling besar menggunakan max()
            max_id = max(int(k[1:]) for k in self.dataProduksi.keys())
            
            # Tambah 1 dari angka terbesar, lalu kembalikan dalam format string 3 digit
            return f"P{max_id + 1:03d}"

    # Tambah data produksi
    def tambahProduksi(self):
        print("\n===== TAMBAH PRODUKSI =====")

        idProduksi = self.generateId()
        print(f"ID Produksi : {idProduksi}")

        modelProduk = input("Model Produk : ")
        jumlahProduksi = int(input("Jumlah Produksi : "))
        prioritasProduksi = input("Prioritas Produksi (Low/Medium/High) : ")
        
        # ----------------------------------------------------
        # AUTO-GENERATE TANGGAL HARI INI SAAT DATA DITAMBAHKAN
        # ----------------------------------------------------
        tanggalMulaiProduksi = date.today().strftime("%Y-%m-%d")
        print(f"Tanggal Mulai Produksi : {tanggalMulaiProduksi} (Otomatis)")

        produksi = Produksi(
            idProduksi,
            modelProduk,
            jumlahProduksi,
            "-", # Tanggal terima diset default kosong sampai barang selesai
            prioritasProduksi,
            "Menunggu Produksi",
            tanggalMulaiProduksi
        )

        self.dataProduksi[idProduksi] = produksi
        import heapq
        heapq.heappush(self.antreanProduksi, produksi)
        self.riwayatProduksi.append(produksi)

        print("Data berhasil ditambahkan.")

    # Cari berdasarkan ID
    def cariProduksi(self):
        idProduksi = input("Masukkan ID Produksi : ").strip().upper()

        if idProduksi in self.dataProduksi:
            self.dataProduksi[idProduksi].tampilData()
        else:
            print("Data tidak ditemukan.")

    # ========================================================
    # Perbaikan Indentasi: Fungsi ini sekarang masuk ke dalam class AdminProduksi
    # ========================================================
    def prosesProduksi(self):
        if not self.antreanProduksi:
            print("Tidak ada antrean produksi.")
            return
        print("\n===== ANTREAN PRODUKSI =====")
        # Akan otomatis memanggil __lt__ dari class Produksi untuk mengurutkan High -> Medium -> Low
        antrean = sorted(self.antreanProduksi)
        for produksi in antrean:
            produksi.tampilData()

    # Menerima hasil produksi
    def penerimaanProduksiSelesai(self):
        idProduksi = input("Masukkan ID Produksi : ").strip().upper()
        jumlahProduksi = int(input("Masukkan Jumlah Produksi : "))

        if idProduksi not in self.dataProduksi:
            print("ID tidak ditemukan.")
            return

        produksi = self.dataProduksi[idProduksi]
        jumlahProduksi += jumlahProduksi

        if produksi.statusProduksi == "Selesai":
            print("Produksi sudah selesai sebelumnya.")
            
        elif produksi.statusProduksi == "Menunggu Produksi":
            produksi.setStatus("Selesai")
            
            # ----------------------------------------------------
            # AUTO-GENERATE TANGGAL HARI INI SAAT BARANG DITERIMA
            # ----------------------------------------------------
            produksi.tanggalTerimaProduksi = date.today().strftime("%Y-%m-%d")
            
            # Hapus dari antrean produksi karena sudah selesai
            if produksi in self.antreanProduksi:
                self.antreanProduksi.remove(produksi)
                heapq.heapify(self.antreanProduksi)
                
            print("Produksi berhasil diterima. Tanggal terima otomatis tersimpan!")
        else:
            print("Status produksi tidak valid.")

    # Menampilkan riwayat
    def lihatRiwayat(self):
        if not self.riwayatProduksi:
            print("Belum ada riwayat.")
            return
        print("\n===== RIWAYAT PRODUKSI =====")
        riwayat = sorted(
            self.riwayatProduksi,
            key=lambda x: x.idProduksi
        )
        for produksi in riwayat:
            produksi.tampilData()

# ==========================
# CLASS MAIN (Menu)
# ==========================
class Main:
    def __init__(self):
        self.admin = AdminProduksi()

    def tampilMenu(self):
        print("\n========== MENU UTAMA ==========")
        print("1. Tambah Produksi")
        print("2. Cari Produksi ID")
        print("3. Lihat Proses Produksi")
        print("4. Lihat Riwayat")
        print("5. Terima Hasil Produksi")
        print("6. Keluar")
        print("================================")

    def pilihMenu(self):
        while True:
            self.tampilMenu()
            pilihan = input("\nPilih Menu : ")

            if pilihan == "1":
                self.admin.tambahProduksi()
            elif pilihan == "2":
                self.admin.cariProduksi()
            elif pilihan == "3":
                self.admin.prosesProduksi()
            elif pilihan == "4":
                self.admin.lihatRiwayat()
            elif pilihan == "5":
                self.admin.penerimaanProduksiSelesai()
            elif pilihan == "6":
                print("Program selesai.")
                break
            else:
                print("Pilihan tidak valid.")

            kembali = input("\nKembali ke menu utama? (y/n): ")
            if kembali.lower() != "y":
                print("Program selesai.")
                break   

# ==========================
# PROGRAM UTAMA
# ==========================
if __name__ == "__main__":
    app = Main()
    app.pilihMenu()