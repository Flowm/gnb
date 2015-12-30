/*    */ package scs.controllers;
/*    */ 
/*    */ import java.io.IOException;
/*    */ import javafx.event.ActionEvent;
/*    */ import javafx.fxml.FXMLLoader;
/*    */ import javafx.scene.Parent;
/*    */ import javafx.scene.Scene;
/*    */ import javafx.scene.control.Button;
/*    */ import javafx.scene.control.Label;
/*    */ import javafx.scene.control.TextField;
/*    */ import javafx.stage.Stage;
/*    */ import scs.Main;
/*    */ import scs.utils.HashUtils;
/*    */ 
/*    */ public class LoginController
/*    */ {
/*    */   public TextField pinTextField;
/*    */   public Label message;
/*    */   
/*    */   public void login(ActionEvent actionEvent) throws IOException
/*    */   {
/* 22 */     String pin = this.pinTextField.getText();
/* 23 */     if (pin.length() != 6) {
/* 24 */       this.message.setText("Please enter a 6-digit PIN");
/*    */     } else {
/* 26 */       HashUtils.setPIN(pin);
/* 27 */       showNextScene(actionEvent);
/*    */     }
/*    */   }
/*    */   
/*    */   private void showNextScene(ActionEvent actionEvent) throws IOException {
/* 32 */     Button btn = (Button)actionEvent.getSource();
/* 33 */     Stage stage = (Stage)btn.getScene().getWindow();
/* 34 */     Parent root = (Parent)FXMLLoader.load(Main.class.getResource("view/details.fxml"));
/* 35 */     Scene scene = new Scene(root);
/* 36 */     stage.setScene(scene);
/* 37 */     stage.show();
/*    */   }
/*    */ }


/* Location:              /Users/lorenzodonini/gnb/phase4/Team7/src/Smart_Card_Simulator.jar!/scs/controllers/LoginController.class
 * Java compiler version: 8 (52.0)
 * JD-Core Version:       0.7.1
 */