package com.uisrel.scaneolo;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.os.StrictMode;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class Escanear extends AppCompatActivity {

    Button botonScanear;
    EditText tvCodigoBarras;
    TextView tvNombre;
    TextView tvCodigo;
    EditText tvPrecio;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_escanear);

        botonScanear = findViewById(R.id.btnScanner);
        tvCodigoBarras = findViewById(R.id.etBarras);
        tvNombre = findViewById(R.id.tv1Nombre);
        tvCodigo = findViewById(R.id.tv1Codigo);
        tvPrecio = findViewById(R.id.etPrecio);

        botonScanear.setOnClickListener(monClickListener);

    }


    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        IntentResult result = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
        if(result != null){
            if(result.getContents() != null)
                //tvCodigoBarras.setText("El codigo de barras es:\n"+ result.getContents());
                tvCodigoBarras.setText(result.getContents());

            }else {
                tvCodigoBarras.setText("ERROR AL SCANEAR");
            }
    }




    private View.OnClickListener monClickListener = new View.OnClickListener(){
        @Override
        public void onClick(View v) {
            switch (v.getId()){
                case R.id.btnScanner:
                    new IntentIntegrator(Escanear.this).initiateScan();
                    break;
            }
        }
    };


///////////////////////////////////////////////////
public void getData(View V){
    String ws = "http://192.168.1.13:8080/scanealo/post_producto.php";

    //Habilitar permisos
    StrictMode.ThreadPolicy politica = new StrictMode.ThreadPolicy.Builder().permitAll().build();
    StrictMode.setThreadPolicy(politica);
    URL url = null;
    HttpURLConnection conn;

    try {
        url = new URL(ws);
        conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");
        conn.connect();

        BufferedReader in = new BufferedReader((new InputStreamReader(conn.getInputStream())));
        String inputLine;
        StringBuffer response = new StringBuffer();
        String json="";


        while ((inputLine = in.readLine())!=null){
            response.append(inputLine);
        }

        json = response.toString();
        JSONArray jsonArr=null;

        jsonArr = new JSONArray(json);

        String nombre = "";
        String codigo = "";
        String barras = "";
        String precioVenta = "";
        for (int i = 0; i<jsonArr.length();i++){
            JSONObject objeto = jsonArr.getJSONObject(i);
            nombre =objeto.optString("prdnombre");
            codigo =objeto.optString("prdcodigo");
            barras =objeto.optString("prdcodbarrasqr");
            precioVenta =objeto.optString("prdprecioventa");
            // nombre +=objeto.optString("nombre"+"\n");
        }

        tvNombre.setText(nombre);
    }
    catch (MalformedURLException e) {
        e.printStackTrace();
    } catch (IOException e) {
        e.printStackTrace();
    } catch (JSONException e) {
        e.printStackTrace();
    }

}


/*

    public void consumirServicio(View v){
        String cedula= tvCodigogetText().toString();
        String nombre= et2.getText().toString();
        String apellido= et3.getText().toString();
        int edad= Integer.parseInt( et4.getText().toString());
        post servicioTask= new post(this,"http://192.168.1.3/rest/post.php",cedula,nombre,apellido, edad);
        servicioTask.execute();

    }

*/
}