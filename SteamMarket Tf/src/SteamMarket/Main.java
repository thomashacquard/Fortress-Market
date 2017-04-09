package SteamMarket;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.Random;
import java.util.Scanner;
import java.util.concurrent.TimeUnit;
import com.google.gson.Gson;
import com.google.gson.JsonNull;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

public class Main {
	
	
public static void main(String[] args) throws IOException, InterruptedException{
	
	Data Data = new Data(); //appel de la classe Data
	BufferedWriter bw = null;	//initialisation des variables permetant l'écriture des données dans un fichier
	FileWriter fw = null;
	
//obtention des noms des objets dans une liste disponible sur le cloud================================
	  String skinListFileName = "C:\\Users\\Paul\\Google Drive\\Steam Market Data\\SkinList.txt";
	  Scanner scLineNumber = new Scanner(new File(skinListFileName));
	  int amountlines =  0;
	  while (scLineNumber.hasNextLine()) {
		  scLineNumber.nextLine();
		  amountlines++;
	  }
	  scLineNumber.close();
	  System.out.println(amountlines);
	  String Skinlist[] = new String[amountlines];
	  Scanner scValue = new Scanner(new File(skinListFileName));
	  for(int n=0; n< Skinlist.length; n++){
		  Skinlist[n] = scValue.nextLine();
		 System.out.println(Skinlist[n]);
		  }
	  scValue.close();
//obtention des noms des objets dans une liste disponible sur le cloud=================================
	  
	  
	String path = "C:\\Users\\Paul\\Google Drive\\Steam Market Data\\Data.json";
	JsonParser parser = new JsonParser();
	for(int Sk = 0; Sk < Skinlist.length; Sk++){
				System.out.println(Skinlist[Sk]);
				Object obj = parser.parse(new FileReader(path));
				JsonObject jsonObject;
				if(obj instanceof JsonNull){
					jsonObject = new JsonObject();
				}else{
				jsonObject = (JsonObject) obj;}

			Gson gson = new Gson();
		    URL url = new URL("http://steamcommunity.com/market/priceoverview/?appid=440&currency=3&market_hash_name="+URLEncoder.encode(Skinlist[Sk],"UTF-8"));
		    System.out.println(url.toString());
		    HttpURLConnection  connection = (HttpURLConnection) url.openConnection();
		    connection.setConnectTimeout(0);
		    if(connection.getResponseCode() == 429){
		    	System.err.println("Error 429: Pausing 5 Minutes");
		    	TimeUnit.MINUTES.sleep(5);
		    }
		    
		    
		    if(connection.getResponseCode() == 500){ //cas où le lien n'aboutit à aucune valeurs (success:false)
		    	System.out.println("false");
				if(jsonObject.has(Skinlist[Sk])){ //cas où l'objet existait déjà dans la base de données
					JsonObject item = jsonObject.get(Skinlist[Sk]).getAsJsonObject();
					if(item.has("Success")){
					item.remove("Success");}
					if(item.has("Lowest_price")){
					item.remove("Lowest_price");}
					if(item.has("Volume")){
					item.remove("Volume");}
					if(item.has("Median_price")){
					item.remove("Median_price");}
					item.addProperty("Success", false);
				}else{//cas où l'objet existait pas dans la base de données
					JsonObject childItem = new JsonObject();
					childItem.addProperty("Success", false);
					jsonObject.add(Skinlist[Sk], childItem);
				}
				fw = new FileWriter(path);
				bw = new BufferedWriter(fw);
				bw.write(jsonObject.toString());//écriture des données
				if (bw != null)
					bw.close();

				if (fw != null)
					fw.close();
				TimeUnit.SECONDS.sleep((long) (new Random().nextFloat() * (2) + 3));
		    }
		    else
		    
		    
		    {if(connection.getInputStream() != null){
	        BufferedReader in = new BufferedReader(
	                                new InputStreamReader(
	                                connection.getInputStream()));
	        String inputLine;

	    	System.out.println("true");
	
			while((inputLine = in.readLine()) != null){
				
	        	String SteamData = inputLine.replace("€", "");
	        	
	        	boolean success = gson.fromJson(SteamData, Data.class).getSuccess();
	        	String lowest_price = gson.fromJson(SteamData, Data.class).getLowestPrice().replaceAll("€","");
	        	String volume = gson.fromJson(SteamData, Data.class).getVolume();
	        	String median_price = gson.fromJson(SteamData, Data.class).getMedianPrice().replaceAll("€","");
	        	
	        	if(lowest_price != null){
	        		lowest_price.replace(",", ".");}
	        	if(volume != null){
	        		volume.replace(",", "");}
	        	if(median_price != null){
	        		median_price.replace(",", ".");}
	        	
	        	Data.setSuccess(success);
	        	Data.setLowestPrice(lowest_price);
	        	Data.setVolume(volume);
	        	Data.setMedianPrice(median_price);}
			
	        in.close();
	        


			if(jsonObject.has(Skinlist[Sk])){
				JsonObject item = jsonObject.get(Skinlist[Sk]).getAsJsonObject();
				if(item.has("Success")){
				item.remove("Success");}
				if(item.has("Lowest_price")){
				item.remove("Lowest_price");}
				if(item.has("Volume")){
				item.remove("Volume");}
				if(item.has("Median_price")){
				item.remove("Median_price");}
				item.addProperty("success", Data.getSuccess());
				item.addProperty("Lowest_price", Data.getLowestPrice());
				if(Data.getVolume() != null){
				item.addProperty("Volume", Data.getVolume());}
				if(Data.getMedianPrice() != null){
				item.addProperty("Median_price", Data.getMedianPrice());}
			}else{
				JsonObject childItem = new JsonObject();
				childItem.addProperty("success", Data.getSuccess());
				childItem.addProperty("Lowest_price", Data.getLowestPrice());
				if(Data.getVolume() != null){
				childItem.addProperty("Volume", Data.getVolume());}
				if(Data.getMedianPrice() != null){
				childItem.addProperty("Median_price", Data.getMedianPrice());}
				jsonObject.add(Skinlist[Sk], childItem);
			}
			fw = new FileWriter(path);
			bw = new BufferedWriter(fw);
			bw.write(jsonObject.toString());
			if (bw != null)
				bw.close();

			if (fw != null)
				fw.close();
			TimeUnit.SECONDS.sleep((long) (new Random().nextFloat() * (2) + 3));
	        }
		};
}






		
	
}



public static class Data {
    private boolean success;
    private String lowest_price;
	private String volume;
    private String median_price;

    public boolean getSuccess(){
    	return success;
    }	    	    
    public String getLowestPrice(){
    	return lowest_price;
    }
	public String getVolume(){
    	return volume;
    }	    
    public String getMedianPrice(){
    	return median_price;
    }    
    public void setSuccess(boolean success){
    	this.success =  success;
    }	    	    
    public void setLowestPrice(String lowest_price){
    	this.lowest_price = lowest_price;
    }	
    public void setVolume(String volume){
    	this.volume =  volume;
    }	    
    public void setMedianPrice(String median_price){
    	this.median_price =  median_price;
    }
}


}



