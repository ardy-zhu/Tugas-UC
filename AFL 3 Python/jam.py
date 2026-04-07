class JamTangan:
    jumlah_jam = 0

    def __init__(self, merk, harga):
        self.merk = merk
        self.harga = harga
        self.ukiran = None
        JamTangan.jumlah_jam += 1
    

    @classmethod
    def getjumlah_jam(cls):
        return cls.jumlah_jam

    @classmethod
    def jam_dengan_ukiran(cls, merk, harga, teks):
        try:
            cls.validasi_teks(teks)
            obj = cls(merk, harga)
            obj.ukiran = teks
            return obj
        except Exception as e:
            print(f"Error: {e}")


    @staticmethod
    def validasi_teks(teks):
        panjang = len(teks)
        error = []

        if panjang > 40:
            error.append("Teks tidak boleh lebih dari 40 karakter")

        if not teks.isalnum():
            error.append("Teks harus alfanumerik")

        if error:
            pesan_error = " dan ".join(error)
            raise Exception(f"teks yang diingkinkan :{teks}, jumlah teks : {panjang}, {pesan_error}")

    def display_info(self):
        print(f"Merk: {self.merk}, Harga: {self.harga}, Ukiran: {self.ukiran}")


jam1 = JamTangan("Rolex", 50000000)
jam2 = JamTangan("Casio", 1000000)
jam3 = JamTangan("Seiko", 2000000)


jam4 = JamTangan.jam_dengan_ukiran("Omega", 70000000, "Luxury")

JamTangan.jam_dengan_ukiran("Casio", 1000000, "123Lorem ipsum dolor sit amet")
JamTangan.jam_dengan_ukiran("Casio", 1000000, "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor")
JamTangan.jam_dengan_ukiran("Casio", 1000000, "123 Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor")


print(f"Jumlah jam tangan yang dibuat: {JamTangan.jumlah_jam}")