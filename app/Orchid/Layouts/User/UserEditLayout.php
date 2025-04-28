<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Campaign;
use App\Models\Task;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Input::make('user.Cedula')
                ->type('text')
                ->required()
                ->mask([
                    'mask' => '[10-]999-9999999-9',
                ])
                ->title(__('Cedula'))
                ->placeholder(__('Cedula')),

            Input::make('user.emp_number')
                ->type('text')
                ->required()
                ->mask([
                    'mask' => '[CMID]-9999',
                ])
                ->title(__('Emp number'))
                ->placeholder(__('Emp number')),

            Input::make('user.Hire_date')
                ->type('date')
                ->required()
                ->title(__('Hire date'))
                ->placeholder(__('Hire date')),

      Select::make('user.Schedule_type')
          ->options([
              '1' => 'Full Time',
              '2' => 'Part Time',
              '3' => 'Temporary',
              '4' => 'Other',
          ])
                ->title(__('Schedule type'))
                ->placeholder(__('Schedule type')),

            Select::make('user.task_id')
            ->fromModel(Task::class, 'name')
                ->title(__('Task'))
                ->placeholder(__('Task')),

            Select::make('user.Campaign_id')
            ->fromModel(Campaign::class, 'name')
                ->title(__('Campaign'))
                ->placeholder(__('Campaign')),


        ];
    }
}
