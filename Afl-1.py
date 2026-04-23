class Siswa:
    def __init__(self, nama, nilai_matematika, nilai_fisika, nilai_kimia):
        self.nama = nama
        self.nilai_matematika = nilai_matematika
        self.nilai_fisika = nilai_fisika
        self.nilai_kimia = nilai_kimia

    def get_nilai_rata_rata(self):
        total_nilai = self.nilai_matematika + self.nilai_fisika + self.nilai_kimia
        rata_rata = total_nilai / 3
        return rata_rata
    
siswa1 = Siswa("andi", 60, 75, 85)
siswa2 = Siswa("budi", 75, 55, 90)
siswa3 = Siswa("citra", 85, 90, 60)

rata_rata_keseluruhan = (siswa1.get_nilai_rata_rata() +
                          siswa2.get_nilai_rata_rata() + 
                          siswa3.get_nilai_rata_rata()) / 3

print(f"Nilai rata-rata ujian dari ketiga siswa adalah: {rata_rata_keseluruhan}")


class Product:
    def __init__(self, name, price, quantity):
        self.name = name
        self.price = price
        self.quantity = quantity

    def get_total_price(self):
        total_price = self.price * self.quantity
        return total_price
    
class ShoppingCart:
    def __init__(self):
        self.products = []

    def add_item(self, product):
        self.products.append(product)

    def remove_item(self, product_name):
        self.products = [product for product in self.products if product.name != product_name]

    def get_total_price(self):
        total_price = sum(product.get_total_price() for product in self.products)
        return total_price


class Customer:
    def __init__(self, name, email, shopping_cart):
        self.name = name
        self.email = email
        self.shopping_cart = shopping_cart

    def checkout(self):
        total_price = self.shopping_cart.get_total_price()
        print(f"{self.name} has checked out with a total price of: {total_price}")
    
    def clear_cart(self):
        self.shopping_cart.products.clear()

baju1 = Product("Baju Wanita", 200000, 3)
baju2 = Product("Baju Pria", 250000, 2)
baju3 = Product("Baju Anak", 150000, 10)
cart = ShoppingCart()

cart.add_item(baju1)
cart.add_item(baju2)
cart.add_item(baju3)

customer = Customer("Ardy", "ardy@example.com", shopping_cart=cart)
customer.checkout()
customer.clear_cart()

print(len(cart.products))






