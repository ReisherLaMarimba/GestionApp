<?php

namespace App\Orchid\Screens\Campaigns;

use App\Models\Campaign;
use App\Orchid\Layouts\Campaigns\CampaignTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CampaignScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'campaigns' => Campaign::select('id', 'name','description','status')->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Campaigns';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create Campaign')
                ->icon('plus')
                ->modal('createCampaignModal')
                ->method('create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            Layout::modal('createCampaignModal', Layout::rows([
                Input::make('name')
                    ->title('Name')
                    ->placeholder('Insert name'),
                Input::make('description')
                    ->title('Description')
                    ->placeholder('Insert description'),
                Select::make('status')
                    ->title('Status')
                    ->placeholder('Insert status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
                Input::make('billiable_hours')
                ->title('Billable Hours')
                    ->type('number')
                ->placeholder('Insert Billable Hours'),
            ]))->title('Create Campaign'),
            CampaignTable::class,
        ];
    }

    PUBLIC function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'billiable_hours' => 'required|integer|max:255',
        ]);

        Campaign::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'billiable_hours' => $request->billiable_hours,
        ]);
        Toast::success('Campaign created successfully');
    }




    Public function delete(Request $request)
    {
        $campaign = Campaign::findOrFail($request->get('id'));
        $campaign->delete();

        Toast::info(__('Campaign was deleted'));
    }
}
