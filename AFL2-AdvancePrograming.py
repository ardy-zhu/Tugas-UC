# class Item:
#     def __init__(self, title):
#         self.title = title

#     def show_info(self):
#         print("Item:", self.title)

# class Book(Item):
#     def __init__(self, title, author, publisher, pages, genre):
#         super().__init__(title)
#         self.author = author
#         self.publisher = publisher
#         self.pages = pages
#         self.genre = genre

#     def show_info(self):
#         print("Book:")
#         print("Title:", self.title)
#         print("Author:", self.author)
#         print("Publisher:", self.publisher)
#         print("Pages:", self.pages)
#         print("Genre:", self.genre)

# class Furniture(Item):
#     def __init__(self, title, material, size):
#         super().__init__(title)
#         self.material = material
#         self.size = size

#     def show_info(self):
#         print("Furniture:")
#         print("Title:", self.title)
#         print("Material:", self.material)
#         print("Size:", self.size)

# class Library:
#     def __init__(self):
#         self.items = []

#     def add_item(self, item):
#         self.items.append(item)

#     def remove_item(self, title):
#         self.items = [item for item in self.items if item.title != title]

#     def show_all_items(self):
#         for item in self.items:
#             item.show_info()
#             print()


# lib = Library()

# book1 = Book("The Lord of the Rings", "J.R.R. Tolkien", "HarperCollins", 1178, "Fantasy")
# book2 = Book("Physics for Scientists and Engineers", "Raymond A. Serway", "Brooks/Cole", 1328, "Textbook")
# book3 = Book("National Geographic", "-", "National Geographic Society", 146, "Magazine")

# furniture1 = Furniture("Chair", "Wood", "50x50x90 cm")
# furniture2 = Furniture("Desk", "Steel", "120x60x75 cm")
# furniture3 = Furniture("Shelf", "Particleboard", "100x30x150 cm")

# lib.add_item(book1)
# lib.add_item(book2)
# lib.add_item(book3)
# lib.add_item(furniture1)
# lib.add_item(furniture2)
# lib.add_item(furniture3)

# lib.show_all_items()

# lib.remove_item("The Lord of the Rings")
# print("After removing 'The Lord of the Rings':")    
# lib.show_all_items()


# # class bangunDatar:
# #     def __init__(self, nama):
# #         self.nama = nama

# #     def info(self):
# #         pass

# # class bangunRuang:
# #     def __init__(self, nama):
# #         self.nama = nama

# #     def info(self):
# #         pass

# # class Persegi(bangunDatar):
# #     def __init__(self, nama, sisi):
# #         super().__init__(nama)
# #         self.sisi = sisi

# #     def luas(self):
# #         return self.sisi * self.sisi
    
# # class persegiPanjang(bangunDatar):
# #     def __init__(self, nama, panjang, lebar):
# #         super().__init__(nama)
# #         self.panjang = panjang
# #         self.lebar = lebar

# #     def luas(self):
# #         return self.panjang * self.lebar
    
# # class Lingkaran(bangunDatar):
# #     def __init__(self, nama, jari_jari):
# #         super().__init__(nama)
# #         self.jari_jari = jari_jari

# #     def luas(self):
# #         return 3.14 * self.jari_jari * self.jari_jari
    
# # class Kubus(bangunRuang):
# #     def __init__(self, nama, sisi):
# #         super().__init__(nama)
# #         self.sisi = sisi

# #     def volume(self):
# #         return self.sisi * self.sisi * self.sisi

# # class Bola(bangunRuang):
# #     def __init__(self, nama, jari_jari):
# #         super().__init__(nama)
# #         self.jari_jari = jari_jari

# #     def volume(self):
# #         return (4/3) * 3.14 * self.jari_jari * self.jari_jari * self.jari_jari
    

# # def menu_bangun_datar():
# #     print("\nPilih jenis bangun datar yang akan dihitung:")
# #     print("1. Persegi")
# #     print("2. Persegi Panjang")
# #     print("3. Lingkaran")
# #     print("4. Kembali")
# #     pilihan = input("Pilih (1-4): ")
# #     if pilihan == '1':
# #         sisi = float(input("Masukkan panjang sisi persegi: "))
# #         persegi = Persegi("Persegi", sisi)
# #         print(f"Luas Persegi: {persegi.luas()}")
# #     elif pilihan == '2':
# #         panjang = float(input("Masukkan panjang: "))
# #         lebar = float(input("Masukkan lebar: "))
# #         pp = persegiPanjang("Persegi Panjang", panjang, lebar)
# #         print(f"Luas Persegi Panjang: {pp.luas()}")
# #     elif pilihan == '3':
# #         r = float(input("Masukkan jari-jari lingkaran: "))
# #         lingkaran = Lingkaran("Lingkaran", r)
# #         print(f"Luas Lingkaran: {lingkaran.luas()}")
# #     elif pilihan == '4':
# #         return
# #     else:
# #         print("Pilihan tidak valid!")
# #     menu_bangun_datar()


# # def main_menu():
# #     while True:
# #         print("\nMenghitung Luas dan Volume Bangun")
# #         print("--------------------------------")
# #         print("1. Bangun Datar")
# #         print("2. Bangun Ruang")
# #         print("3. Exit")
# #         pilihan = input("Pilih menu (1-3): ")
# #         if pilihan == '1':
# #             menu_bangun_datar()
# #         elif pilihan == '2':
# #             bangun_ruang()
# #         elif pilihan == '3':
# #             print("Terima kasih!")
# #             break
# #         else:
# #             print("Pilihan tidak valid!")

# # def bangun_ruang():
# #     print("\nPilih jenis bangun ruang yang akan dihitung:")
# #     print("1. Kubus")
# #     print("2. Bola")
# #     print("3. Kembali")
# #     pilihan = input("Pilih (1-3): ")
# #     if pilihan == '1':
# #         sisi = float(input("Masukkan panjang sisi kubus: "))
# #         kubus = Kubus("Kubus", sisi)
# #         print(f"Volume Kubus: {kubus.volume()}")
# #     elif pilihan == '2':
# #         r = float(input("Masukkan jari-jari bola: "))
# #         bola = Bola("Bola", r)
# #         print(f"Volume Bola: {bola.volume()}")
# #     elif pilihan == '3':
# #         return
# #     else:
# #         print("Pilihan tidak valid!")
# #     bangun_ruang()


# # if __name__ == "__main__":
# #     main_menu()
    
def repeat(num_repeats):
    def decorator(func):
        def wrapper(*args, **kwargs):
            for i in range(num_repeats):
                func(*args, **kwargs)
        return wrapper
    return decorator
@repeat(3)
def say_hello(name):
    print(f"Hello, {name}!")
say_hello("Alice")