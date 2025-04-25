<?php

namespace App\Orchid\Layouts\Tasks;

use App\Http\Requests\TaskRequest;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class TaskTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'tasks';

    //TODO: Add a filter to search by name

    protected function textNotFound(): string
    {
        return 'There are no tasks';
    }
    protected function subNotFound(): string
    {
        return 'Here you can view and edit all the tasks';
    }



    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name'),
            TD::make('description', 'Description'),

            //Count user that have this task assigned

            TD::make('user_count', 'Users assigned')
                ->render(fn ($task) => $task->users()->count()),

            TD::make('Actions')
                ->CantHide()
                ->render(fn ($task)=> DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Edit Task')
                        ->route('platform.tasks.edit', $task->id)
                        ->icon('bs.pencil'),

                     Button::make('Delete Task')
                        ->icon('trash')
                         ->confirm(__('Once the task is deleted, all of its resources and data will be permanently deleted. All the agents under this task will be reassigned to BLANK'))
                        ->method('delete', [
                            'id' => $task->id,
                        ])
                ]))

        ];
    }


}
