@extends('layouts.app')

@php

    $months = [
        'January' => 'Styczeń',
        'February' => 'Luty',
        'March' => 'Marzec',
        'April' => 'Kwiecień',
        'May' => 'Maj',
        'June' => 'Czerwiec',
        'July' => 'Lipiec',
        'August' => 'Sierpień',
        'September' => 'Wrzesień',
        'October' => 'Październik',
        'November' => 'Listopad',
        'December' => 'Grudzień',
    ];

    $daysOfWeek = ['P', 'W', 'Ś', 'C', 'P', 'S', 'N'];

    use Carbon\Carbon;

    $taskInstance = collect($taskInstance);

    $taskByDate = $taskInstance->groupBy(function ($task) {
        return Carbon::parse($task->scheduled_for)->toDateString();
    }) 

@endphp

@section('content')
    <div class="calendar box">
        <div class="title">
            <h1>Kalendarz</h1>
        </div>
        <div class="content">
            <div class="calendar-panel">
                <div class="calendar-month">
                    {{ ($months[$currentMonth->format('F')] ?? '') . ' ' . $currentMonth->format('Y') }}
                </div>
                <div class="calendar-holder">
                    <div class="calendar-left">
                        <button onclick="changeMonth(-1)">
                            <img src="{{ asset('images/left.png') }}" alt="left" width="60">
                        </button>
                    </div>
                    <div class="calendar-center">
                        @foreach ($daysOfWeek as $day)
                            <div class="day-label">
                                {{ $day }}
                            </div>
                        @endforeach
                        @foreach ($days as $day)
                            @if ($day)

                                @php
                                    $dateString = $day->toDateString();
                                    $tasksForDay = $taskByDate[$dateString] ?? collect();

                                    $mappedTask = $tasksForDay->map(function ($instance) {
                                        return [
                                            'id' => $instance->id,
                                            'user_id' => $instance->user_id,
                                            'scheduled_for' => $instance->scheduled_for,
                                            'completed_at' => $instance->completed_at,
                                            'status' => $instance->status,
                                            'task_name' => optional($instance->task)->name,
                                            'description' => optional($instance->task)->description,
                                        ];
                                    })
                                @endphp

                                <div class="calendar-day" data-date="{{ $dateString }}" data-tasks='@json($mappedTask)'
                                    onclick="handleDayClick(this)" @if ($day->isSameDay($today))
                                    style="background-color: lightgreen ; color: #000;" @endif @if ($tasksForDay->isNotEmpty())
                                    style="background-color: #FDE68A ;" @endif>
                                    {{ $day->day }}
                                </div>
                            @else
                                <div class="calendar-day empty"></div>
                            @endif
                        @endforeach
                    </div>
                    <div class="calendar-right">
                        <button onclick="changeMonth(1)">
                            <img src="{{ asset('images/right.png') }}" alt="right" width="60">
                        </button>
                    </div>
                </div>

                <div class="task-panel" id="task-panel" style="display: none;">
                    <h2>Zadania na <span id="selected-day"></span></h2>
                    <div class="task-panel-task" id="task-panel-task">


                    </div>
                    <div class="task-instance-info" id="task-instance-info">

                    </div>
                    <button onclick="closeTaskPanel()" class="delete">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const changeMonth = (offset) => {
            const parms = new URLSearchParams(window.location.search)
            const currentMonth = parms.get('month') || "{{ now()->format('Y-m') }}";

            if (!currentMonth) {
                currentMonth = "{{ $currentMonth->format('Y-m') }}";
            }

            const [year, month] = currentMonth.split('-').map(Number);
            const date = new Date(year, month - 1);
            date.setMonth(date.getMonth() + offset);
            const newMonth = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            window.location.href = `?month=${newMonth}`;
        }

        const handleDayClick = (element) => {
            document.querySelectorAll('.calendar-day').forEach(div => {
                div.classList.remove('active');
            });

            element.classList.add('active');
            const date = element.dataset.date;
            let tasks = [];

            try {
                tasks = JSON.parse(element.dataset.tasks);
            } catch (e) {
                console.error('Błąd json: ', e);
            }

            openTaskPanel(date, tasks);

        }

        const openTaskPanel = (date, task_instance = null) => {
            document.getElementById('task-instance-info').textContent = '';
            document.getElementById('task-panel-task').textContent = '';
            if (task_instance && task_instance.length > 0) {
                //console.log(task_instance);
                task_instance.forEach((instance, i) => {
                    const taskDiv = document.createElement('div');
                    taskDiv.classList.add('task-item');
                    taskDiv.dataset.id = instance.id;
                    taskDiv.dataset.description = instance.description ?? '(Brak)';
                    taskDiv.dataset.scheduled_for = instance.scheduled_for;
                    taskDiv.dataset.completed_at = instance.completed_at ?? '---';
                    taskDiv.dataset.status = instance.status;
                    taskDiv.innerHTML = `<h3>${i + 1}. ${instance.task_name ?? '(brak)'}</h3>`

                    console.log(instance.task_id);
                    document.getElementById('task-panel-task').appendChild(taskDiv);
                });
            } else {
                document.getElementById('task-panel-task').textContent = 'Brak zadań na ten dzień.';
            }
            document.getElementById('selected-day').textContent = date;
            document.getElementById('task-panel').style.display = 'block';
        }

        const statuses = {
            'not finished': 'nie ukończone',
            'pending': 'w trakcie',
            'done': 'zrobione'
        };

        const new_statuses = ['not finished', 'pending', 'done'];


        document.getElementById('task-panel-task').addEventListener('click', (e) => {
            if (e.target.closest('.task-item')) {
                const taskDiv = e.target.closest('.task-item');
                const taskDescription = taskDiv.dataset.description;
                const taskScheduled_for = taskDiv.dataset.scheduled_for;
                const taskCompleted_at = taskDiv.dataset.completed_at;
                const taskStatus = taskDiv.dataset.status;;
                document.getElementById('task-instance-info').innerHTML = `
                                                                        <hr>
                                                                        <p>Opis: ${taskDescription}</p>
                                                                        <p>Zakończone w: ${taskCompleted_at || '---'}</p>
                                                                        <h5>Status: ${statuses[taskStatus] || 'Nieznany'}</h5>
                                                                        <hr>
                                                                    `;
                new_statuses.forEach(element => {
                    const btn = document.createElement('button');
                    btn.style.backgroundColor = 'transparent';
                    btn.style.color = '#000';
                    btn.textContent = `Ustaw status na: '${statuses[element]}'`;
                    btn.addEventListener('click', () => {
                        updateTaskStatus(taskDiv.dataset.id, element);
                    });
                    document.getElementById('task-instance-info').appendChild(btn);
                });

                const br = document.createElement('br');
                document.getElementById('task-instance-info').appendChild(br);
                const closeBtn = document.createElement('button');
                closeBtn.textContent = 'zamknij szczegóły';
                closeBtn.classList.add('delete');
                closeBtn.addEventListener('click', () => {
                    document.getElementById('task-instance-info').innerHTML = '';
                });

                document.getElementById('task-instance-info').appendChild(closeBtn);
            }
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const updateTaskStatus = (taskInstanceId, newStatus) => {
            fetch(`/task-instance/${taskInstanceId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Status zaktualizowany!');
                        //document.querySelector(`.task-item[data-id="${taskInstanceId}"]`).dataset.status = newStatus;
                        window.location.reload();
                    } else {
                        alert('Błąd aktualizacji statusu');
                    }
                })
                .catch(err => console.error("Błąd zapytania:", err));
        }


        const closeTaskPanel = () => {
            //console.log('CLOSE')
            document.getElementById('task-panel').style.display = 'none';
        }
    </script>
@endsection