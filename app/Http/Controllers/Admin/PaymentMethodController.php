<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('baslik')->paginate(20);
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        PaymentMethod::create($request->all());

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Ödeme yöntemi başarıyla eklendi.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', ['method' => $paymentMethod]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $paymentMethod->update($request->all());

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Ödeme yöntemi başarıyla güncellendi.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Ödeme yöntemi başarıyla silindi.');
    }
}
