<?php

namespace App\Orchid\Screens\tasks;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class TaskEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $task;
    public function query(Task $task): iterable
    {
        $this->task = $task;
        return ['task' => $task];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editing: ' . $this->task->name;
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

            Link::make('Task List')
            ->icon('list')
            ->route('platform.tasks'),
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
            Layout::columns([
                Layout::rows([
                    Input::make('name')
                    ->title('Name')
                    ->value($this->task->name)
                        ->help('Inserte el nombre del artículo')
                    ->placeholder('Inserte el nombre del artículo'),


                ]),
                Layout::rows([
                    Input::make('description')
                    ->title('Description')
                    ->value($this->task->description)
                    ->placeholder('Inserte la descripción del artículo')
                    ->help('Inserte la descripción del artículo'),
                ]),
            ])

        ];

    }

    Public function update(TaskRequest $taskRequest, Task $task)
    {
        $task->update($taskRequest->all());

        Toast::info(__('Task was updated'));
    }
}
