
#polimorphism
# class KartuKredit:
#     def bayar(self, jumlah):
#         print("Pembayaran sebesar", jumlah, "berhasil menggunakan Kartu Kredit!")
        
# class DompetDigital:
#     def bayar(self, jumlah):
#        print("Pembayaran sebesar", jumlah, "berhasil menggunakan Dompet Digital!")
       
# class UangTunai:
#     def bayar(self, jumlah):
#         print("Pembayaran sebesar", jumlah, "berhasil menggunakan Uang Tunai!")

# def lakukanPembayaran(jenisPembayaran, jumlah):
#     jenisPembayaran.bayar(jumlah)

# jenisPembayaran_kartu = KartuKredit()
# jenisPembayaran_dompet = DompetDigital()
# jenisPembayaran_tunai = UangTunai()

# lakukanPembayaran(jenisPembayaran_kartu, 100000)
# lakukanPembayaran(jenisPembayaran_dompet, 50000)
# lakukanPembayaran(jenisPembayaran_tunai, 20000)



# class Mesin:
#     def __init__(self, jenis):
#         self.jenis = jenis

#     def nyalakan(self):
#         return self.jenis


# class Motor:
#     def __init__(self, mesin):
#         self.mesin = mesin   # composition

#     def start(self):
#         print(f"Motor dengan mesin {self.mesin.nyalakan()} dinyalakan.")


# class Mobil:
#     def __init__(self, mesin):
#         self.mesin = mesin

#     def start(self):
#         print(f"Mobil dengan mesin {self.mesin.nyalakan()} dinyalakan.")


# class Bus:
#     def __init__(self, mesin):
#         self.mesin = mesin

#     def start(self):
#         print(f"Bus dengan mesin {self.mesin.nyalakan()} dinyalakan.")


# # Membuat objek mesin
# mesin_bensin = Mesin("Bensin")
# mesin_listrik = Mesin("Listrik")

# # Membuat kendaraan dengan mesin yang sesuai
# motor = Motor(mesin_bensin)
# mobil = Mobil(mesin_listrik)
# bus = Bus(mesin_bensin)

# # Menyalakan kendaraan
# motor.start()
# mobil.start()
# bus.start()

class Animal:
    def __init__(self, name):
        self.name = name
    def speak(self):
        pass

class Dog(Animal):
    def speak(self):
        return "woof"

class Cat(Animal):
    def speak(self):
        return "meow"

def animal_sound(animal):
    return animal.speak()

my_dog = Dog("Buddy"); print(animal_sound(my_dog))