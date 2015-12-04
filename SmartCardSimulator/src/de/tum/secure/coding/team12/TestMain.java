package de.tum.secure.coding.team12;

/**
 * Created by lorenzodonini on 03/12/15.
 *
 * ONLY USED FOR TEST PURPOSES
 */
public class TestMain {
    public static void main(String[] args) {
        String pin = "123456";
        String iban = "10000001";
        String amount = "100";
        for (int i=0; i<10; i++) {
            String result = CustomTanGenerator.generateTAN(pin+iban+amount);
            try {
                Thread.sleep(1000);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            System.out.println(result);
        }
    }
}
