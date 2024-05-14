<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Lookup;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function graph(Request $request)
    {
        $positions = Lookup::all();
        $colors = [
            "#FF0000", // Red
            "#00FF00", // Green
            "#0000FF", // Blue
            "#FFFF00", // Yellow
            "#FF00FF", // Magenta
            "#00FFFF", // Cyan
            "#800080", // Purple
            // Add more colors as needed
        ];
        $data = [];
        $label = [];

        foreach ($positions as $key => $position) {
            $total = App::where('position_category_id', $position->id);

            if ($request->all()) {

                /**
                 * filter for gender
                 */
                if ($request->input('gender')) {
                    if ($request->input('gender') == 'L') {
                        $total = $total->whereRaw("SUBSTRING(nokp, -1) % 2 != 0");
                    } else {
                        $total = $total->whereRaw("SUBSTRING(nokp, -1) % 2 = 0");
                    }
                }

                /**
                 * filter for date
                 */
                if ($request->input('date')) {
                    $total = $total->whereDate('file_date', $request->input('date'));
                }
            }

            $label[] = substr($position->value, -4);
            $data[] = $total->count();
        }

        return view('dashboard', [
            'data' => $data,
            'label' => $label,
        ]);
    }
}
