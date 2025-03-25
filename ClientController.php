<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ClientController 
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:my_client',
            'client_prefix' => 'required',
            'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $clientData = $request->all();

       
        // if ($request->hasFile('client_logo')) {
        //     $path = $request->file('client_logo')->store('clients', 's3');
        //     $clientData['client_logo'] = Storage::disk('s3')->url($path);
        // }

      
        $client = Client::create($clientData);

     
        Redis::set("client:{$client->slug}", json_encode($client));

        return response()->json($client, 201);
    }

   
    public function show($id)
    {
        $client = Client::find($id);
        if (!$client) return response()->json(['message' => 'Client not found'], 404);

        return response()->json($client);
    }


    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client) return response()->json(['message' => 'Client not found'], 404);

        $client->update($request->all());

        
        Redis::del("client:{$client->slug}");

        
        Redis::set("client:{$client->slug}", json_encode($client));

        return response()->json($client);
    }

   
    public function destroy($id)
    {
        $client = Client::find($id);
        if (!$client) return response()->json(['message' => 'Client not found'], 404);

        $client->delete();

       
        Redis::del("client:{$client->slug}");

        return response()->json(['message' => 'Client deleted']);
    }
}
