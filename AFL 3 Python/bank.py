class akun_error(Exception):
    pass


class akun_bank:
    def __init__(self, nomor_rekening, saldo):
        self.__nomor_rekening = nomor_rekening
        self.__saldo = saldo

    @property
    def nomor_rekening(self):
        return self.__nomor_rekening

    @nomor_rekening.setter
    def nomor_rekening(self, nomor_baru):
        raise akun_error("Nomor rekening tidak dapat diubah")

    @property
    def saldo(self):
        return self.__saldo

    @saldo.setter
    def saldo(self, saldo):
        if saldo >= 0:
            self.__saldo = saldo
        else:
            raise akun_error("Saldo tidak boleh negatif")
            
    def get_saldo(self):
        if self.__saldo < 0:
            raise akun_error("Saldo tidak boleh negatif")
        return self.__saldo
    
    
    def lihat_rekening(self):
        print(f"Nomor Rekening: {self.__nomor_rekening}, Saldo: {self.get_saldo()}")
        if self.__nomor_rekening == "1234567890":
            raise akun_error("Nomor rekening tidak valid")
        else:
            print("Nomor rekening valid")

    def tarik(self, jumlah):
        if jumlah > self.__saldo:
            print("Saldo tidak cukup untuk melakukan penarikan")
        else:
            self.__saldo -= jumlah
            if jumlah > 1000000:
                print("Penarikan Dalam Jumlah Besar, diperlukan audit")

    def setor(self, jumlah):
        if jumlah <= 0:
            print("Jumlah setoran harus lebih besar dari nol")
        else:
            self.__saldo += jumlah
            if jumlah > 1000000:
                print("Penyetoran Dalam Jumlah Besar, diperlukan audit")

    def hapus_akun(self):
        if self.__saldo == 0:
            print("Akun berhasil dihapus")
        else:
            print("Gagal menghapus akun, saldo tidak nol")


akun_1 = akun_bank("1234567880", 1000000)
akun_1.lihat_rekening()
print(f"Nomor akun1: {akun_1.nomor_rekening}")

try:
    akun_1.saldo = -500000
except akun_error as e:
    print(f"{e}")

try:
    akun_1.nomor_rekening = "1111111111"
except akun_error as e:
    print(f"{e}")

try:
    akun_1.setor(5000000)
except akun_error as e:
    print(f"{e}")

try:
    akun_1.tarik(2000000)
except akun_error as e:
    print(f"Error: {e}")

try:
    akun_1.saldo = 500000
    akun_1.tarik(50000000)
except akun_error as e:
    print(f"Error: {e}")


try:
    akun_1.saldo = 100
    akun_1.hapus_akun()
except akun_error as e:
    print(f"Error: {e}")