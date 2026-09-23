<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use Illuminate\Http\Request;

class AdSlotController extends Controller
{
    public function index()
    {
        $slots = AdSlot::orderBy('id')->get();
        return view('admin.ad_slots.index', compact('slots'));
    }

    public function edit(AdSlot $adSlot)
    {
        return view('admin.ad_slots.edit', compact('adSlot'));
    }

    public function update(Request $request, AdSlot $adSlot)
    {
        $request->validate([
            'label'     => 'required|string|max:100',
            'code'      => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $adSlot->update([
            'label'     => $request->input('label'),
            'code'      => $request->input('code') ?: null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('ad_slots.index')->with('success', "Đã cập nhật: {$adSlot->label}");
    }

    public function toggle(AdSlot $adSlot)
    {
        $adSlot->update(['is_active' => !$adSlot->is_active]);
        return redirect()->back()->with('success', $adSlot->is_active ? 'Đã bật quảng cáo.' : 'Đã tắt quảng cáo.');
    }
}
