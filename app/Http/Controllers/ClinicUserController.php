<?php
// app/Http/Controllers/ClinicUserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicUserModel;

class ClinicUserController extends Controller
{
  public function create()
  {
    return view('clinic-users-info.cui-registration');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'clinic_user_name' => 'required|string|max:255',
      'furigana' => 'nullable|string|max:255',
      'birthday' => 'nullable|date',
      'age' => 'nullable|integer|min:0|max:150',
      'gender_id' => 'nullable|integer|in:1,2',
      'postal_code' => 'nullable|string|max:8',
      'address_1' => 'nullable|string|max:255',
      'address_2' => 'nullable|string|max:255',
      'address_3' => 'nullable|string|max:255',
      'phone' => 'nullable|string|max:20',
      'cell_phone' => 'nullable|string|max:20',
      'fax' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'housecall_distance' => 'nullable|integer|min:0',
      'housecall_additional_distance' => 'nullable|integer|min:0',
      'is_redeemed' => 'nullable|boolean',
      'application_count' => 'nullable|integer|min:0',
      'note' => 'nullable|string|max:1000'
    ]);

    // チェックボックスの処理
    $validated['is_redeemed'] = $request->has('is_redeemed');

    ClinicUserModel::create($validated);

    return redirect()->back()->with('success', '利用者情報が保存されました。');
  }
}
