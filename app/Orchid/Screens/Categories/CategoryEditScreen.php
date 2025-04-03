<?php

namespace App\Orchid\Screens\Categories;

use App\Http\Requests\CategoriesRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */

    public $category;

    public function query(Category $category): array
    {
        $this->category = $category;
        return [
            'category' => $category,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Editando categoria: ' . $this->category->name . ' De prioridad: ' . $this->category->risk;
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
                    Input::make('category.name')
                        ->title('Nombre')
                        ->value($this->category->name)
                        ->required(),

                    Input::make('category.description')
                        ->title('Descripcion')
                        ->value($this->category->description)
                        ->required(),

                    Select::make('category.risk')
                        ->title('Riesgo')
                        ->options([
                            'Alta' => 'Alta',
                            'Medio' => 'Medio',
                            'Baja' => 'Baja',
                        ])
                        ->value($this->category->risk)
                        ->required(),
                ]),


        ];
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category.name' => 'required|string|max:255',
            'category.description' => 'required|string|max:255',
            'category.risk' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $validated['category']['name'],
            'description' => $validated['category']['description'],
            'risk' => $validated['category']['risk'],
        ]);


        Toast::info('Categoria actualizada exitosamente');
        return redirect()->route('platform.categories');
    }

    public function cancelEdit()
    {
        return redirect()->route('platform.categories');
    }

}
