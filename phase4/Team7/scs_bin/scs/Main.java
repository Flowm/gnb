/*    */ package scs;
/*    */ 
/*    */ import javafx.application.Application;
/*    */ import javafx.fxml.FXMLLoader;
/*    */ import javafx.scene.Parent;
/*    */ import javafx.scene.Scene;
/*    */ import javafx.stage.Stage;
/*    */ 
/*    */ public class Main extends Application
/*    */ {
/*    */   public void start(Stage primaryStage) throws Exception
/*    */   {
/* 13 */     Parent root = (Parent)FXMLLoader.load(getClass().getResource("view/login.fxml"));
/* 14 */     primaryStage.setTitle("SCS");
/* 15 */     primaryStage.setScene(new Scene(root));
/* 16 */     primaryStage.show();
/*    */   }
/*    */   
/*    */   public static void main(String[] args)
/*    */   {
/* 21 */     launch(args);
/*    */   }
/*    */ }


/* Location:              /Users/lorenzodonini/gnb/phase4/Team7/src/Smart_Card_Simulator.jar!/scs/Main.class
 * Java compiler version: 8 (52.0)
 * JD-Core Version:       0.7.1
 */