import java.util.Scanner;

public class Main {
    private static void printList(MyArrayList list) {
        Object[] arr = list.toArray();
        for (Object obj : arr) {
            if (obj != null) {
                System.out.print(obj + " ");
            }
        }
        System.out.println();
    }

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        MyArrayList list = new MyArrayList();

        System.out.print("Berapa data yang ingin dimasukkan? ");
        int jumlah = Integer.parseInt(scanner.nextLine());

        for (int i = 0; i < jumlah; i++) {
            System.out.print("Data ke-" + (i + 1) + ": ");
            String input = scanner.nextLine();
            list.add(input);
        }

        System.out.println("\nSebelum remove:");
        printList(list);

        System.out.print("Elemen yang ingin dihapus: ");
        String targetRemove = scanner.nextLine();
        boolean removed = list.remove(targetRemove);
        System.out.println("Apakah berhasil dihapus? " + removed);

        System.out.println("\nSetelah remove:");
        printList(list);

        System.out.print("Elemen yang ingin dicari index-nya: ");
        String targetIndex = scanner.nextLine();
        int index = list.indexOf(targetIndex);
        System.out.println("Index " + targetIndex + " = " + index);

        System.out.print("\nYakin ingin clear list? (yes/no): ");
        String confirm = scanner.nextLine().trim().toLowerCase();

        if (confirm.equals("yes") || confirm.equals("y")) {
            list.clear();
            System.out.println("Setelah clear:");
            System.out.println("List Empty[]");
        } else {
            System.out.println("Clear dibatalkan.");
            System.out.println("Isi list saat ini:");
            printList(list);
        }

        scanner.close();
    }
}