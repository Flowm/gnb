/*    */ package scs.controllers;
/*    */ 
/*    */ import java.io.File;
/*    */ import java.io.PrintStream;
/*    */ import java.nio.file.NoSuchFileException;
/*    */ import javafx.event.ActionEvent;
/*    */ import javafx.scene.Scene;
/*    */ import javafx.scene.control.Button;
/*    */ import javafx.scene.control.CheckBox;
/*    */ import javafx.scene.control.Label;
/*    */ import javafx.scene.control.TextField;
/*    */ import javafx.scene.paint.Color;
/*    */ import javafx.stage.FileChooser;
/*    */ import javafx.stage.Stage;
/*    */ import scs.utils.HashUtils;
/*    */ 
/*    */ public class DetailsController
/*    */ {
/*    */   public TextField amountTextField;
/*    */   public TextField accountTextField;
/*    */   public TextField bfcTextField;
/*    */   public Button openButton;
/*    */   public Button generateTAN;
/*    */   public CheckBox isBatch;
/*    */   public Label result;
/*    */   public TextField tan;
/*    */   
/*    */   public void checkBatch(ActionEvent actionEvent)
/*    */   {
/* 30 */     if (this.isBatch.isSelected()) {
/* 31 */       deactivateSingleTransactionControls(true);
/*    */     } else {
/* 33 */       deactivateSingleTransactionControls(false);
/*    */     }
/*    */   }
/*    */   
/*    */   private void deactivateSingleTransactionControls(boolean active) {
/* 38 */     this.amountTextField.setDisable(active);
/* 39 */     this.accountTextField.setDisable(active);
/* 40 */     this.openButton.setDisable(!active);
/* 41 */     this.bfcTextField.setDisable(!active);
/*    */   }
/*    */   
/*    */   public void selectFile(ActionEvent actionEvent) {
/* 45 */     FileChooser chooser = new FileChooser();
/* 46 */     Button btn = (Button)actionEvent.getSource();
/* 47 */     Stage stage = (Stage)btn.getScene().getWindow();
/* 48 */     File file = chooser.showOpenDialog(stage);
/* 49 */     if (file != null) {
/* 50 */       this.bfcTextField.setText(file.getAbsolutePath());
/*    */     }
/*    */   }
/*    */   
/*    */   public void generateTAN(ActionEvent actionEvent) {
/*    */     try {
/* 56 */       if (this.isBatch.isSelected()) {
/* 57 */         if (this.bfcTextField.getText().isEmpty()) {
/* 58 */           showError("The path must be specified.");
/*    */         } else {
/*    */           try {
/* 61 */             String hash = HashUtils.hashBatchFile(this.bfcTextField.getText());
/* 62 */             showTAN(hash);
/*    */           } catch (NoSuchFileException e) {
/* 64 */             showError("Invalid file path");
/*    */           }
/*    */         }
/*    */       }
/*    */       else {
/* 69 */         String amount = this.amountTextField.getText();
/* 70 */         String account = this.accountTextField.getText();
/* 71 */         if ((amount.isEmpty()) || (account.isEmpty())) {
/* 72 */           showError("Please fill in " + (amount.isEmpty() ? "the amount" : "the account"));
/*    */         } else {
/* 74 */           String hash = HashUtils.hashSingleTransaction(account, amount);
/* 75 */           showTAN(hash);
/*    */         }
/*    */       }
/*    */     } catch (Exception e) {
/* 79 */       System.out.print("Cool error handling " + e.getMessage());
/*    */     }
/*    */   }
/*    */   
/*    */   private void showTAN(String hash) {
/* 84 */     this.result.setTextFill(Color.BLACK);
/* 85 */     this.tan.setVisible(true);
/* 86 */     this.tan.setText(hash);
/* 87 */     this.result.setText("TAN: ");
/*    */   }
/*    */   
/*    */   private void showError(String message) {
/* 91 */     this.result.setTextFill(Color.RED);
/* 92 */     this.result.setText(message);
/* 93 */     this.tan.setVisible(false);
/*    */   }
/*    */ }


/* Location:              /Users/lorenzodonini/gnb/phase4/Team7/src/Smart_Card_Simulator.jar!/scs/controllers/DetailsController.class
 * Java compiler version: 8 (52.0)
 * JD-Core Version:       0.7.1
 */