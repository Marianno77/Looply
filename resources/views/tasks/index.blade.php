@extends('layouts.app')

@php
    $days = [
        1 => 'Poniedziałek',
        2 => 'Wtorek',
        3 => 'Środa',
        4 => 'Czwartek',
        5 => 'Piątek',
        6 => 'Sobota',
        0 => 'Niedziela'
    ];
@endphp

@section('content')
    <div class="tasks box">
        <div class="title">
            <h1>Formularze</h1>
        </div>
        <div class="content">
            <div class="tasks-panel">
                <div class="add-task" id="add-task-form">
                    <h2>Dodaj nowe zadanie</h2>
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <section>
                            <label for="name">Nazwa zadania:</label>
                            <input name="name" type="text" value="{{ old('name') }}">
                        </section>

                        <section>
                            <label for="description">Opis zadania:</label>
                            <input name="description" type="text" value="{{ old('description') }}">
                        </section>

                        <section>
                            <label for="repeat_type">Typ powtarzalności:</label>
                            <select name="repeat_type">
                                <option value="daily">Dziene</option>
                                <option value="weekly">Tygodniowe</option>
                                <option value="monthly">Miesięczne</option>
                            </select>
                        </section>

                        <section>
                            <label for="repeat_interval">Intensywność powtarzania (co ile powtarzać):</label>
                            <input name="repeat_interval" type="number" min="1" value="{{ old('repeat_interval') }}">
                        </section>
                        <section>
                            <label>Wyklucz dni tygodnia:</label>
                            <div class="exclude_days">
                                @foreach ($days as $value => $label)
                                    <label>
                                        <input type="checkbox" name="exclude_days[]" value="{{ $value }}"> {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </section>
                        <section>
                            <label for="exclude_holidays">Wykluczyć święta?</label>
                            <input type="radio" name="exclude_holidays" id="true-holidays" value="0">
                            <label for="true-holidays">Tak</label>
                            <input type="radio" name="exclude_holidays" id="false-holidays" value="1">
                            <label for="false-holidays">Nie</label>
                        </section>

                        <section>
                            <label for="start_date">Data początkowa:</label>
                            <input name="start_date" type="date" value="{{ old('start_date') }}">
                        </section>

                        <section>
                            <label for="end_date">Data końcowa:</label>
                            <input name="end_date" type="date" value="{{ old('end_date') }}">
                        </section>

                        <section>
                            <label for="completed">Czy zadanie jest wykonane:</label>
                            <input type="radio" name="completed" id="true-completed" value="0">
                            <label for="true-completed">Tak</label>
                            <input type="radio" name="completed" id="false-completed" value="1">
                            <label for="false-completed">Nie</label>
                        </section>


                        <button type="submit">Dodaj zadanie</button>
                    </form>

                    @if ($errors->any())
                        <div class="errors">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <hr>

                <h2>Twoje zadania</h2>
                <div class="all-tasks">
                    <table>
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Typ</th>
                                <th>Data rozpoczęcia</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $types = [
                                    'daily' => 'Dziene',
                                    'weekly' => 'Tygodniowe',
                                    'monthly' => 'Miesięczne',
                                ]
                            @endphp
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $types[$task->repeat_type] ?? 'Nieznany typz' }}</td>
                                    <td>{{ $task->start_date }}</td>
                                    <td>
                                        <button type="button" onclick='openEditForm(@json($task))'>Edytuj</button>

                                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="task-edit" id="task-edit" style="display: none;">
                    <h2>Edytuj zadanie</h2>
                    <form method="POST" id="task-edit-form">
                        @csrf
                        @method('PUT')

                        <section>
                            <label>Nazwa zadania:</label>
                            <input type="text" name="name" id="task_name">
                        </section>

                        <section>
                            <label>Opis zadania:</label>
                            <input type="text" name="description" id="task_description">
                        </section>

                        <section>
                            <label>Typ powtarzalności:</label>
                            <select name="repeat_type" id="task_repeat_type">
                                <option value="daily">Dzienne</option>
                                <option value="weekly">Tygodniowe</option>
                                <option value="monthly">Miesięczne</option>
                            </select>
                        </section>

                        <section>
                            <label>Intensywność powtarzania (co ile powtarzać):</label>
                            <input type="number" name="repeat_interval" id="task_repeat_interval" min="1">
                        </section>

                        <section>
                            <label>Wyklucz dni tygodnia:</label>
                            <div id="task_exclude_days">
                                @foreach ($days as $val => $label)
                                    <label><input type="checkbox" name="exclude_days[]" value="{{ $val }}"> {{ $label }}</label>
                                @endforeach
                            </div>
                        </section>

                        <section>
                            <label>Wykluczyć święta?</label>
                            <label><input type="radio" name="exclude_holidays" value="1"> Tak</label>
                            <label><input type="radio" name="exclude_holidays" value="0"> Nie</label>
                        </section>

                        <section>
                            <label>Data początkowa:</label>
                            <input type="date" name="start_date" id="task_start_date">
                        </section>

                        <section>
                            <label>Data końcowa:</label>
                            <input type="date" name="end_date" id="task_end_date">
                        </section>

                        <section>
                            <label>Czy zadanie jest wykonane:</label>
                            <label><input type="radio" name="completed" value="1"> Tak</label>
                            <label><input type="radio" name="completed" value="0"> Nie</label>
                        </section>

                        <button type="submit">Zapisz</button>
                    </form>
                    <button id="close" onclick="closeForm()">Zamknij formularz</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const closeForm = () => {
            document.getElementById('task-edit').style.display = 'none';
        }

        const openEditForm = (task) => {
            console.clear();
            console.log('Task: ', task);

            const form = document.getElementById('task-edit-form');
            document.getElementById('task-edit').style.display = 'flex';

            form.action = `/tasks/${task.id}`;
            document.getElementById('task_name').value = task.name ?? '';
            document.getElementById('task_description').value = task.description ?? '';
            document.getElementById('task_repeat_type').value = task.repeat_type;
            document.getElementById('task_repeat_interval').value = task.repeat_interval ?? 1;
            document.getElementById('task_start_date').value = task.start_date ?? '';
            document.getElementById('task_end_date').value = task.end_date ?? '';

            document.querySelectorAll('#task_exclude_days input[type="checkbox"]').forEach(chk => {
                chk.checked = (task.exclude_days ?? []).includes(parseInt(chk.value));
            });

            document.querySelectorAll('input[name="exclude_holidays"]').forEach(radio => {
                radio.checked = parseInt(radio.value) === task.exclude_holidays;
            });

            document.querySelectorAll('input[name="completed"]').forEach(radio => {
                radio.checked = parseInt(radio.value) === task.completed;
            });
        }
    </script>

@endsection