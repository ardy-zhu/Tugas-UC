class Node:
	def __init__(self, data):
		self.data = data
		self.prev = None
		self.next = None


class MyDoubleLinkedList:
	def __init__(self):
		self.head = None
		self.tail = None

	def add_first(self, e):
		node = Node(e)
		if self.head is None:
			self.head = self.tail = node
		else:
			node.next = self.head
			self.head.prev = node
			self.head = node

	def add_last(self, e):
		node = Node(e)
		if self.tail is None:
			self.head = self.tail = node
		else:
			node.prev = self.tail
			self.tail.next = node
			self.tail = node

	def remove_first(self):
		if self.head is None:
			raise IndexError("Deque kosong")
		value = self.head.data
		self.head = self.head.next
		if self.head is None:
			self.tail = None
		else:
			self.head.prev = None
		return value

	def remove_last(self):
		if self.tail is None:
			raise IndexError("Deque kosong")
		value = self.tail.data
		self.tail = self.tail.prev
		if self.tail is None:
			self.head = None
		else:
			self.tail.next = None
		return value

	def get_first(self):
		if self.head is None:
			raise IndexError("Deque kosong")
		return self.head.data

	def get_last(self):
		if self.tail is None:
			raise IndexError("Deque kosong")
		return self.tail.data

	def to_list(self):
		items = []
		current = self.head
		while current is not None:
			items.append(current.data)
			current = current.next
		return items


class Deque:
	# type 0 = larang insertFront
	# type 1 = larang insertRear
	# type 2 = larang removeFront
	# type 3 = larang removeRear
	def __init__(self, type):
		if type not in (0, 1, 2, 3):
			raise ValueError("type harus 0,1,2,3")
		self.list = MyDoubleLinkedList()
		self.type = type

	def insertFront(self, e):
		try:
			if self.type == 0:
				raise PermissionError("insertFront tidak diizinkan untuk type 0")
			self.list.add_first(e)
		except PermissionError as err:
			print("Error insertFront:", err)

	def insertRear(self, e):
		try:
			if self.type == 1:
				raise PermissionError("insertRear tidak diizinkan untuk type 1")
			self.list.add_last(e)
		except PermissionError as err:
			print("Error insertRear:", err)

	def removeFront(self):
		try:
			if self.type == 2:
				raise PermissionError("removeFront tidak diizinkan untuk type 2")
			return self.list.remove_first()
		except (PermissionError, IndexError) as err:
			print("Error removeFront:", err)
			return None

	def removeRear(self):
		try:
			if self.type == 3:
				raise PermissionError("removeRear tidak diizinkan untuk type 3")
			return self.list.remove_last()
		except (PermissionError, IndexError) as err:
			print("Error removeRear:", err)
			return None

	def getFront(self):
		try:
			return self.list.get_first()
		except IndexError as err:
			print("Error getFront:", err)
			return None

	def getRear(self):
		try:
			return self.list.get_last()
		except IndexError as err:
			print("Error getRear:", err)
			return None

	def showList(self):
		print("List:", self.list.to_list())


if __name__ == "__main__":
	
	print("=== Contoh data 10, 20, 30 ===")
	dq_normal = Deque(0)  # type 0: insertFront dilarang
	dq_normal.insertRear(10)
	dq_normal.showList()
	dq_normal.insertRear(20)
	dq_normal.showList()
	dq_normal.insertRear(30)
	dq_normal.showList()
	print("getFront():", dq_normal.getFront())
	print("getRear():", dq_normal.getRear())
	print("removeFront():", dq_normal.removeFront())
	dq_normal.showList()
	print("removeRear():", dq_normal.removeRear())
	dq_normal.showList()

	print("\n=== Contoh method insertFront (yang diizinkan) ===")
	dq_insert_front_ok = Deque(1)  # type 1: insertRear dilarang, insertFront diizinkan
	dq_insert_front_ok.insertFront(10)
	dq_insert_front_ok.showList()
	dq_insert_front_ok.insertFront(20)
	dq_insert_front_ok.showList()
	dq_insert_front_ok.insertFront(30)
	dq_insert_front_ok.showList()
	print("getFront():", dq_insert_front_ok.getFront())
	print("getRear():", dq_insert_front_ok.getRear())

	print("\n=== Contoh error operasi terlarang berdasarkan type ===")
	dq_normal.insertFront(10)  # type 0 -> insertFront dilarang
	dq_insert_front_ok.showList()
	dq_insert_front_ok.insertRear(20)  # type 1 -> insertRear dilarang
	dq_insert_front_ok.showList()

	dq_remove_front_forbidden = Deque(2)  # type 2: removeFront dilarang
	dq_remove_front_forbidden.insertFront(30)
	dq_remove_front_forbidden.insertRear(40)
	dq_remove_front_forbidden.showList()
	dq_remove_front_forbidden.removeFront()
	dq_remove_front_forbidden.showList()

	dq_remove_rear_forbidden = Deque(3)  # type 3: removeRear dilarang
	dq_remove_rear_forbidden.insertFront(30)
	dq_remove_rear_forbidden.insertRear(40)
	dq_remove_rear_forbidden.showList()
	dq_remove_rear_forbidden.removeRear()
	dq_remove_rear_forbidden.showList()
