<?php

namespace App\View\Components\Dashboard;

use App\Models\Division;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GeoLocationPicker extends Component
{
    public $divisions;

    public function __construct(public $divisionId = '', public $districtId = '', public $upazilaId = '')
    {
        $this->divisions = Division::active()->get();
    }

    public function render(): View
    {
        return view('components.dashboard.geo-location-picker');
    }
}
