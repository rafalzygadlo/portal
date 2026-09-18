<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use App\Modules\ModuleManager;
use Livewire\Component;
use App\Models\ModuleOrder;

class Modules extends Component
{
    public Company $company;
    public array $cart = [];

    public function mount(Company $company): void
    {
        $this->company = $company;
    }

    public function cartItems(): array
{
    $items = [];

    foreach ($this->cart as $slug) {
        $module = app(ModuleManager::class)->find($slug);

        if ($module) {
            $items[] = $module;
        }
    }

    return $items;
}

public function cartTotal(): int
{
    $total = 0;

    foreach ($this->cartItems() as $module) {
        $total += $module->price();
    }

    return $total;
}

  public function toggleModule(string $slug): void
{
    $module = app(ModuleManager::class)->find($slug);

    if (!$module) {
        abort(404);
    }

    if ($this->company->hasModule($slug)) {
        return;
    }

    if (in_array($slug, $this->cart, true)) {
        $this->cart = array_values(
            array_diff($this->cart, [$slug])
        );

        return;
    }

    $this->cart[] = $slug;
}
public function checkout(): void
{
    if (empty($this->cart)) {
        return;
    }

    $modules = $this->cartItems();

    $order = ModuleOrder::create([
        'company_id' => $this->company->id,
        'amount' => $this->cartTotal(),
        'billing_period' => 'monthly',
        'status' => 'pending',
    ]);

    foreach ($modules as $module) {
        $order->items()->create([
            'module' => $module->slug(),
            'amount' => $module->price(),
        ]);
    }

    $this->cart = [];
}
    public function render()
    {
        $moduleManager = app(ModuleManager::class);

        return view('livewire.admin.company.modules', [
            'cartItems' => $this->cartItems(),
            'cartTotal' => $this->cartTotal(),
            'modules' => $moduleManager->instances(),
        ])->layout('layouts.company', [
                    'company' => $this->company,
                ]);
    }
}