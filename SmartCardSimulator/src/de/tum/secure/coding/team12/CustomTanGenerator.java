package de.tum.secure.coding.team12;

import org.apache.commons.codec.binary.Base64;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

/**
 * Created by lorenzodonini on 03/12/15.
 */
public class CustomTanGenerator {
    private static final String HASH_ALGORITHM = "SHA-256";
    private static final int HASH_LENGTH = 15;
    private static final int SALT_LENGTH = 5;
    private static final long SALT_MAX = (long)Math.pow(64,5); //64 characters ** 5 fields
    private static final String BASE64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

    public static String generateTAN(String valueToHash) {
        //We first generate a random SALT
        long now = System.currentTimeMillis()/1000;
        long saltLong = now % SALT_MAX;

        //Going to convert the single values to Base64
        StringBuilder sb = new StringBuilder();
        encodeLongBase64(saltLong,(long)64,sb);
        String salt = sb.toString();
        salt = ("00000" + salt).substring(salt.length());

        byte [] saltBase64 = salt.getBytes();
        byte [] content = valueToHash.getBytes();

        //Concatenating bytes into a destination array
        byte [] data = new byte[saltBase64.length + content.length];
        int i=0, j;
        while (i < content.length) {
            data[i] = content[i++];
        }
        j = i;
        i = 0;
        while (i < saltBase64.length) {
            data[j++] = saltBase64[i++];
        }

        byte [] hash = generateHash(data);
        if (hash == null) {
            return null;
        }
        byte [] hashBase64 = Base64.encodeBase64(hash);

        byte [] result = new byte[HASH_LENGTH];
        //Copying the first 10 bytes of the hash
        i = 0;
        while (i < HASH_LENGTH - SALT_LENGTH) {
            result[i] = hashBase64[i++];
        }

        //Copying the salt
        j = i;
        i = 0;
        while (i < SALT_LENGTH) {
            result[j++] = saltBase64[i++];
        }

        return new String(result);
    }

    private static void encodeLongBase64(Long number, Long base, StringBuilder sb) {
        if (number < base) {
            sb.append(BASE64.charAt(number.intValue()));
        }
        else {
            encodeLongBase64(number/base,base,sb);
            sb.append(BASE64.charAt(((Long)(number % base)).intValue()));
        }
    }

    private static byte [] generateHash(byte [] data) {
        try {
            MessageDigest hasher = MessageDigest.getInstance(HASH_ALGORITHM);
            return hasher.digest(data);

        } catch (NoSuchAlgorithmException e) {
            //Something happened
            return null;
        }
    }
}
