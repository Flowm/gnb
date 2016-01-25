package de.tum.secure.coding.team12;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.math.BigDecimal;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by lorenzodonini on 01/12/15.
 */
public class Presenter {
    private MainView mView;
    private Map<Integer, String> mErrorMap;

    public Presenter() {
        mView = new MainView(this);
        mErrorMap = new HashMap<Integer,String>();
        //Statically set the possible errors
        mErrorMap.put(OperationResult.CODE_PIN_FORMAT,"Invalid PIN format");
        mErrorMap.put(OperationResult.CODE_DESTINATION_FORMAT,"Invalid IBAN format");
        mErrorMap.put(OperationResult.CODE_AMOUNT_FORMAT,"Invalid amount format");
        mErrorMap.put(OperationResult.CODE_FILE,"File was not found");
        mErrorMap.put(OperationResult.CODE_FILE_EXT,"Invalid file extension. Only TXT and CSV files are allowed");
        mErrorMap.put(OperationResult.CODE_FILE_LINE,"Format error found on file line ");
        mErrorMap.put(OperationResult.CODE_GENERIC_ERROR,"An error occurred while parsing your data");
    }

    public void showGUI() {
        mView.setVisible(true);
    }

    /**
     * Generates a TAN for a single transaction. We only need to know who the receiver of the
     * transfer will be, and how much money the user wants to transfer.
     * @param pin  The PIN of the user
     * @param destination  The IBAN of the receiver
     * @param amnt  The amount to transfer
     * @return  returns a random TAN
     */
    public OperationResult generateTAN(String pin, String destination, String amnt) {
        Integer pw = parsePin(pin);
        if (pw == null) {
            return new OperationResult(OperationResult.CODE_PIN_FORMAT);
        }
        Integer iban = parseIban(destination);
        if (iban == null) {
            return new OperationResult(OperationResult.CODE_DESTINATION_FORMAT);
        }
        BigDecimal amount = parseAmount(amnt);
        if (amount == null) {
            return new OperationResult(OperationResult.CODE_AMOUNT_FORMAT);
        }
        String tan = CustomTanGenerator.generateTAN(pin+destination+amnt);
        return (tan != null) ? new OperationResult(OperationResult.CODE_OK, tan) :
                new OperationResult(OperationResult.CODE_GENERIC_ERROR);
    }

    /**
     * Generates a TAN for a multiple transaction. We get all the necessary information from the batch file.
     * @param pin  The PIN of the user
     * @param file  The batch file containing the info of the transactions
     * @return  returns a random TAN
     */
    public OperationResult generateTAN(String pin, File file) {
        Integer pw = parsePin(pin);
        if (pw == null) {
            return new OperationResult(OperationResult.CODE_PIN_FORMAT);
        }
        if (!file.exists()) {
            return new OperationResult(OperationResult.CODE_FILE);
        }
        String extension = "";

        int i = file.getName().lastIndexOf('.');
        if (i >= 0) {
            extension = file.getName().substring(i+1);
        }
        if (extension.equals("")
                || !(extension.equalsIgnoreCase("txt") || extension.equalsIgnoreCase("csv"))) {
            return new OperationResult(OperationResult.CODE_FILE_EXT);
        }
        String textToHash = parseFile(file);
        try {
            int line = Integer.parseInt(textToHash); //If this works we want to report the line as well
            return new OperationResult(OperationResult.CODE_FILE_LINE, Integer.toString(line));
        }
        catch(Exception e) {
            //File is actually valid. Can continue
        }
        //Generating the actual tan
        String tan = CustomTanGenerator.generateTAN(textToHash+pin);
        return (tan != null) ? new OperationResult(OperationResult.CODE_OK, tan) :
                new OperationResult(OperationResult.CODE_GENERIC_ERROR);
    }

    /**
     * This only gets called if the file exists and the extension of the file has been confirmed as valid.
     * The file is then parsed and based on the read input, a TAN will be generated
     * @param file  The batch file
     * @return  returns a random TAN if no errors occurred while reading the file, null otherwise
     */
    private String parseFile(File file) {
        StringBuilder sb = new StringBuilder();
        try {
            BufferedReader reader = new BufferedReader(new FileReader(file));
            String line;
            int count = 1;
            while ((line = reader.readLine()) != null) {
                if (line.equals("")) {
                    //We might bump into an empty line (probably at the end)
                    count++;
                    continue;
                }
                String values [] = line.split(",");
                if (values.length != 3) {
                    return Integer.toString(count);
                }
                //Checking iban
                Integer iban = parseIban(values[0]);
                if (iban == null) {
                    return Integer.toString(count);
                }
                sb.append(values[0]);
                //Checking amount
                BigDecimal amount = parseAmount(values[1]);
                if (amount == null) {
                    return Integer.toString(count);
                }
                sb.append(values[1]);
                //Checking description
                if (values[2].length() == 0) {
                    return Integer.toString(count);
                }
                count++;
            }
        }
        catch (Exception e) {
            return Integer.toString(OperationResult.CODE_FILE);
        }

        return sb.toString();
    }

    private Integer parsePin(String pin) {
        if (pin == null || pin.length() != 6) {
            return null;
        }
        try {
            Double result = Double.parseDouble(pin);
            //We already checked the length before. If we can parse it as a double, then it's fine
            return result.intValue();
        }
        catch (Exception e) {
            return null;
        }
    }

    private Integer parseIban(String iban) {
        if (iban == null || iban.equals("")) {
            return null;
        }
        try {
            Double result = Double.parseDouble(iban);
            double minimum = 10000000;
            double maximum = 99999999;
            if (result < minimum || result > maximum) {
                return null;
            }
            return result.intValue();
        }
        catch (Exception e) {
            return null;
        }
    }

    private BigDecimal parseAmount(String amount) {
        try {
            BigDecimal result = new BigDecimal(amount);
            BigDecimal minimum = new BigDecimal(0);
            if (result.compareTo(minimum) <= 0) {
                return null;
            }
            return result;
        }
        catch (Exception e) {
            return null;
        }
    }

    public class OperationResult {
        public int code;
        public String result;

        public static final int CODE_OK = 0;
        public static final int CODE_PIN_FORMAT = 1;
        public static final int CODE_DESTINATION_FORMAT = 2;
        public static final int CODE_AMOUNT_FORMAT = 3;
        public static final int CODE_FILE = 5;
        public static final int CODE_FILE_EXT = 6;
        public static final int CODE_FILE_LINE = 7;
        public static final int CODE_GENERIC_ERROR = 8;

        public OperationResult(int resultCode) {
            code = resultCode;
            if (code > CODE_OK) {
                result = mErrorMap.get(code);
            }
        }

        public OperationResult(int resultCode, String res) {
            code = resultCode;
            if (code == CODE_OK) {
                result = res;
            }
            else {
                result = mErrorMap.get(code);
                if (resultCode == CODE_FILE_LINE) {
                    result += res;
                }
            }
        }
    }
}
