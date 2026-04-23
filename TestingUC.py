class Base1:
    def do_something(self):
        print("Method defined in Base1")

class Base2:
    def do_something(self):
        print("Method defined in Base2")

class MultiDerived(Base1, Base2):
    pass

my_object = MultiDerived(); my_object.do_something()