<?php

namespace App\Http\Controllers;

use App\Models\Additional;
use App\Models\Item;
use App\Models\ItemUser;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function PrintAssigments(Request $request){
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user  = User::where('Id',$request->user_id)
            ->with('task')
            ->first();

        $ItemsAssignedToUser = ItemUser::where('user_id', $request->user_id)
            ->with(['item.category'])
            ->get()
            ->groupBy('item.category.name')
            ->map(function ($items) {
                return $items->pluck('item');
            });

        if(isset($ItemsAssignedToUser['CPU'])){
            $cpuExtract = $ItemsAssignedToUser['CPU'];

            $cpu = Item::where('id', $cpuExtract[0]->id)
                ->first();

            $additionalsID = $cpu->additionals;

            $addtionals = Additional::whereIn('id', $additionalsID)->get();

        }




//return $ItemsAssignedToUser;

        $pdf = Pdf::loadView('Assignments.equipment_assignment',['imagePath' => 'images/cmaxlogo.png', 'itemsAssignedToUser' => $ItemsAssignedToUser, 'user' => $user, 'Additionals' => $addtionals]);
        $pdf->setPaper('letter' );
        return $pdf->stream('equipment_assignment.pdf');


    }
}
