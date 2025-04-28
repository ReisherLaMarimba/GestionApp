<?php

namespace App\Orchid\Layouts\Campaigns;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class CampaignTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'campaigns';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->cantHide(),
            TD::make('description', 'Description')
                ->cantHide(),
            TD::make('status', 'Status')
                ->cantHide(),

            TD::make('Actions')
            ->CantHide()
            ->render(fn ($campaign) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Edit Campaign')
                        ->route('platform.Campaigns.edit', ['campaign' => $campaign->id])
                        ->icon('bs.pencil'),

                    Button::make('Delete Campaign')
                        ->icon('trash')
                        ->confirm(__('Once the campaign is deleted, all of its resources and data will be permanently deleted. All the agents under this campaign will be reassigned to BLANK'))
                        ->method('delete', [
                            'id' => $campaign->id,
                        ])
                    ]))
        ];


    }

}
