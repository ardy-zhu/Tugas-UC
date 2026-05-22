import java.util.Collection;

public class MyArrayList {
    private Object[] elements;
    private int size;
    final int DEFAULT_CAPACITY = 10;

    public MyArrayList() {
        elements = new Object[DEFAULT_CAPACITY];
    }

    public MyArrayList(int cap) {
        elements = new Object[cap];
    }

    public MyArrayList(Collection<?> c) {
        elements = c.toArray();
        size = elements.length;
    }

    private void grow() {
        Object[] temp = elements;
        int newCapacity = (temp.length == 0) ? DEFAULT_CAPACITY : temp.length * 2;
        elements = new Object[newCapacity];
        for (int i = 0; i < temp.length; i++)
            elements[i] = temp[i];
    }

    public boolean add(Object element) {
        if (size == elements.length) {
            grow();
        }
        elements[size] = element;
        size++;
        return true;
    }

    public boolean isEmpty() {
        return (size == 0);
    }

    public boolean remove(Object element) {
        for (int i = 0; i < size; i++) {
            boolean isMatch = (element == null && elements[i] == null)
                    || (element != null && element.equals(elements[i]));

            if (isMatch) {
                for (int j = i; j < size - 1; j++) {
                    elements[j] = elements[j + 1];
                }
                elements[size - 1] = null;
                size--;
                return true;
            }
        }
        return false;
    }

    public int indexOf(Object element) {
        for (int i = 0; i < size; i++) {
            boolean isMatch = (element == null && elements[i] == null)
                    || (element != null && element.equals(elements[i]));
            if (isMatch) {
                return i;
            }
        }
        return -1;
    }

    public void clear() {
        for (int i = 0; i < size; i++) {
            elements[i] = null;
        }
        size = 0;
    }

    public Object[] toArray() {
        Object[] result = new Object[size];

        for (int i = 0; i < size; i++) {
            result[i] = elements[i];
        }

        return result;
    }
}