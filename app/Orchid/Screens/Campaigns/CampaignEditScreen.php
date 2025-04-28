<?php

namespace App\Orchid\Screens\Campaigns;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CampaignEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $campaign;
    public function query( Campaign $campaign): iterable
    {
        $this->campaign = $campaign;
        return [
            'campaign' => $campaign,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit Campaign: ' . $this->campaign->name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save Changes')
                ->icon('save')
                ->method('update'),

            Button::make('Cancel')
                ->icon('close')
                ->method('cancelEdit'),

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
            Layout::rows([
            Input::make('campaign.name')
                ->title('Name')
                ->value($this->campaign->name)
                ->required(),
            Input::make('campaign.description')
                ->title('Description')
                ->value($this->campaign->description)
                ->required(),
            Select::make('campaign.status')
                ->title('Status')
                ->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ])
                ->value($this->campaign->status)
                ->required(),
                ]),
        ];
    }

    public function update(Request $request)
    {
       $validated = $request->validate([
           'campaign.name' => 'required|string|max:255',
           'campaign.description' => 'required|string|max:255',
           'campaign.status' => 'required|string|max:255',
       ]);

       $this->campaign->update([
           'name' => $validated['campaign']['name'],
           'description' => $validated['campaign']['description'],
           'status' => $validated['campaign']['status'],
       ]);

       Toast::info('Campaign updated successfully');

    }

    public function cancelEdit()
    {
        return redirect()->route('platform.Campaigns');
    }
}
