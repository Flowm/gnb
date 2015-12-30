/*    */ package scs.utils;
/*    */ 
/*    */ import java.math.BigInteger;
/*    */ import java.nio.charset.StandardCharsets;
/*    */ import java.nio.file.Files;
/*    */ import java.nio.file.Paths;
/*    */ import java.security.MessageDigest;
/*    */ import java.util.Random;
/*    */ 
/*    */ 
/*    */ public class HashUtils
/*    */ {
/* 13 */   private static String PIN = "";
/*    */   
/*    */   public static void setPIN(String pin) {
/* 16 */     PIN = pin;
/*    */   }
/*    */   
/*    */   public static String hashSingleTransaction(String account, String amount) throws Exception {
/* 20 */     return hashTAN(new String[] { account, amount });
/*    */   }
/*    */   
/*    */   public static String hashBatchFile(String path) throws Exception {
/* 24 */     byte[] encoded = Files.readAllBytes(Paths.get(path, new String[0]));
/* 25 */     String data = new String(encoded, StandardCharsets.UTF_8);
/* 26 */     return hashTAN(new String[] { data });
/*    */   }
/*    */   
/*    */   private static String hashTAN(String... input) throws Exception {
/* 30 */     String data = PIN;
/* 31 */     for (String s : input) {
/* 32 */       data = data + s;
/*    */     }
/* 34 */     return hash(data);
/*    */   }
/*    */   
/*    */   private static String getRandFiveCharString() {
/* 38 */     return Integer.toString(new Random().nextInt(90000) + 10000);
/*    */   }
/*    */   
/*    */   private static String hash(String data) throws Exception {
/* 42 */     MessageDigest messageDigest = MessageDigest.getInstance("SHA-256");
/* 43 */     String random = getRandFiveCharString();
/* 44 */     data = data + random;
/* 45 */     BigInteger hash = new BigInteger(1, messageDigest.digest(data.getBytes("UTF-8")));
/* 46 */     String hashString = hash.toString(16);
/* 47 */     while (hashString.length() < 64) {
/* 48 */       hashString = "0" + hashString;
/*    */     }
/* 50 */     return hashString.substring(0, 10) + random;
/*    */   }
/*    */ }


/* Location:              /Users/lorenzodonini/gnb/phase4/Team7/src/Smart_Card_Simulator.jar!/scs/utils/HashUtils.class
 * Java compiler version: 8 (52.0)
 * JD-Core Version:       0.7.1
 */