<?php

namespace App\Http\Controllers;

use App\Models\Registrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Regist extends Controller
{
    //
    public function index()
    {
        return view('regist');
    }
    public function handleRegist(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:registrants',
            'phone' => 'required|numeric',
            'nim' => 'required|numeric',
            'school' => 'required|string|max:255',
            'category' => 'required|string|in:category1,category2,category3',
            'isEdu' => 'required|boolean',
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('receipt');
        $fileName = 'images/' . Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            Storage::disk('s3')->put($fileName, file_get_contents($file));
            $url = Storage::disk('s3')->url($fileName);
            echo('File berhasil di-upload');
        } catch (\Exception $e) {
            echo('Failed to upload file: ' . $e->getMessage());
        }
        if (request('category') === 'category1') {
            $validatedData['nominal'] = 100000;
        } elseif (request('category') === 'category2') {
            $validatedData['nominal'] = 200000;
        } else {
            $validatedData['nominal'] = 300000;
        }
        $registrant = Registrant::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'nim' => $validatedData['nim'],
            'school' => $validatedData['school'],
            'category' => $validatedData['category'],
            'nominal' => $validatedData['nominal'], // Use the updated nominal value
            'receipt' => $url,
            'isEdu' => $validatedData['isEdu'],
        ]);
        return redirect()->back()->with('success', 'Regist successfully!');
    }
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);


        return redirect()->route('registrant.index')->with('success', 'Registration successful!');
    }
}
