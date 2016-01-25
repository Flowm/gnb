package de.tum.secure.coding.team12;

import javax.imageio.ImageIO;
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.FocusEvent;
import java.awt.event.FocusListener;
import java.awt.image.BufferedImage;
import java.io.File;

/**
 * Created by lorenzodonini on 30/11/15.
 */
public class MainView extends JFrame {
    private JButton chooseFileButton;
    private JPanel headerPanel;
    private JPanel mainPanel;
    private JPanel singleTransactionPanel;
    private JPanel multipleTransactionPanel;
    private JLabel instructionLabel;
    private JLabel label1;
    private JLabel label2;
    private JPasswordField pinField;
    private JPanel pinPanel;
    private JPanel titlePanel;
    private JPanel bodyPanel;
    private JPanel resultPanel;
    private JPanel fieldPanel;
    private JLabel ibanLabel;
    private JTextField ibanField;
    private JLabel amountLabel;
    private JTextField amountField;
    private JButton generateButton;
    private JLabel resultLabel;
    private JLabel filenameLabel;
    private JLabel pinLabel;
    private JLabel logoLabel;
    private JTextPane pinResult;
    private File chosenFile;
    private Presenter mPresenter;

    private final static String PIN_HINT = "";
    private final static String DEST_ACCOUNT_HINT = "IBAN";
    private final static String AMOUNT_HINT = "Amount";
    private final static String TAN_GENERATED_LABEL = "This is your next TAN: ";

    private static final String ERROR_DOUBLE_INPUT = "Either choose a single or a multiple transaction!";

    public MainView(Presenter controller) {
        super("GNB Banking");
        mPresenter = controller;
        initComponents();
        pack();
        setSize(900,600);
        setResizable(false);
        setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
    }

    protected void initComponents() {
        //Setting content
        setContentPane(mainPanel);

        //Setting hints
        setFocusListenerForField(pinField,PIN_HINT);
        setFocusListenerForField(ibanField,DEST_ACCOUNT_HINT);
        setFocusListenerForField(amountField,AMOUNT_HINT);

        //File chooser
        chooseFileButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                JFileChooser fileChooser = new JFileChooser();
                int result = fileChooser.showOpenDialog(MainView.this);
                if (result == JFileChooser.APPROVE_OPTION) {
                    chosenFile = fileChooser.getSelectedFile();
                    filenameLabel.setText("Selected: "+chosenFile.getName());
                }
                else if (result == JFileChooser.CANCEL_OPTION) {
                    filenameLabel.setText("No file selected!");
                }
            }
        });

        //Button listener
        generateButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                performGenerateTan();
            }
        });

        //GNB Logo
        try {
            BufferedImage img = ImageIO.read(MainView.class.getResourceAsStream("gnb_logo.png"));
            ImageIcon icon = new ImageIcon(img);
            logoLabel.setIcon(icon);
        }
        catch (Exception e) {
            resultLabel.setText("error loading logo");
        }

        //Setting pin result custom properties
        pinResult.setBackground(null);
        pinResult.setBorder(null);
    }

    private void performGenerateTan() {
        String pin = new String(pinField.getPassword());
        String amount = amountField.getText();
        if (amount.equals(AMOUNT_HINT)) {
            amount = "";
        }
        String iban = ibanField.getText();
        if (iban.equals(DEST_ACCOUNT_HINT)) {
            iban = "";
        }
        Presenter.OperationResult result;

        if (chosenFile != null && (!amount.equals("") || !iban.equals(""))) {
            chosenFile = null;
            pinField.setText("");
            ibanField.setText("");
            amountField.setText("");
            resultLabel.setText(ERROR_DOUBLE_INPUT);
            filenameLabel.setText("");
            pinResult.setText("");
            return;
        }
        pinResult.setText("");
        resultLabel.setText("");

        if (chosenFile != null) {
            result = mPresenter.generateTAN(pin, chosenFile);
        }
        else {
            result = mPresenter.generateTAN(pin, iban, amount);
        }
        //Checking result (can't be null)
        if (result.code == Presenter.OperationResult.CODE_OK) {
            pinResult.setText(result.result);
            resultLabel.setText(TAN_GENERATED_LABEL);
        }
        else {
            pinResult.setText("");
            resultLabel.setText(result.result);
        }
    }

    //Setting up hint logic
    private void setFocusListenerForField(final JTextField field, final String defaultText) {
        field.setForeground(Color.DARK_GRAY);
        field.setText(defaultText);
        field.addFocusListener(new FocusListener() {
            @Override
            public void focusGained(FocusEvent e) {
                field.setForeground(Color.BLACK);
                if (field.getText().equals(defaultText)) {
                    field.setText("");
                }
            }

            @Override
            public void focusLost(FocusEvent e) {
                if (field.getText().equals("")) {
                    field.setForeground(Color.DARK_GRAY);
                    field.setText(defaultText);
                }
            }
        });
    }
}
