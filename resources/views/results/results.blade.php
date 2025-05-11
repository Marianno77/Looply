@extends('layouts.app')

@section('content')
    <div class="results box">
        <div class="title">
            <h1>Wyniki</h1>
        </div>
        <div class="content">
            <div class="results-container">
                <h2>Stosunek ukończonych zadań do nie wykonanych</h2>
                <div class="tasks-buttnos">
                    @if (count($tasks) == 0)
                        <div class="message">
                            <p>Tu pojawi się lista twoich zadań!</p>
                            <p>Możesz dodać nowe zadania w zakładce <a href="{{ url('/tasks') }}">'Twoje zadania'</a></p>
                        </div>
                    @endif
                    @foreach ($tasks as $task)
                        @php
                            $done = $task->instance()->where('status', 'done')->count();
                            $nf = $task->instance()->where('status', 'not finished')->count();
                        @endphp
                        <button class="task-btn" data-done="{{ $done }}" data-nf="{{ $nf }}"
                            onclick="showChart(this)">{{ $task->name }}</button>
                    @endforeach
                </div>
                <div class="canvas" id="canvas">
                    <canvas id="pieChart"></canvas>

                    <section>
                        <p id="label"></p>
                        <button onclick="closeChart()" class="delete">Zamknij</button>
                    </section>
                </div>

                <h2>Ilość wykonanych zadań w poszczególnych miesiącach</h2>
                <div class="canvas" id="canvas-two">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const label = document.getElementById('label');
        const canvas = document.getElementById('canvas');
        canvas.style.display = 'none';
        let pieChart = null;

        const showChart = (button) => {
            canvas.style.display = 'block';

            const done = parseInt(button.getAttribute('data-done'), 10);
            const nf = parseInt(button.getAttribute('data-nf'), 10);

            const ctx = document.getElementById('pieChart').getContext('2d');
            label.innerHTML = '';
            if (pieChart) {
                pieChart.destroy();
            }
            if (done !== 0 || nf !== 0) {
                label.innerHTML = 'Zrobione: ' + done + '<br/>' + 'Nie ukończone: ' + nf;
                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Zrobione', 'Nie ukończnone'],
                        datasets: [{
                            label: 'ilość: ',
                            data: [done, nf],
                            backgroundColor: [
                                'rgb(54, 227, 27)',
                                'rgb(235, 64, 52)'
                            ],
                            borderColor: [
                                'rgb(0, 0, 0)',
                                'rgb(0, 0, 0)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: top,
                            },
                            tooltip: {
                                enabled: true,
                            }
                        }
                    }
                })
            } else {
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.font = "30px Arial";
                ctx.fillStyle = "black";
                ctx.textAlign = "center";
                ctx.fillText('Brak danych', ctx.canvas.width / 2, ctx.canvas.height / 2);
            }


            console.log('done:', done);
            console.log('not finished:', nf);
        }

        const closeChart = () => {
            canvas.style.display = 'none';
        }

        const months = @json($months);
        const taskCounts = @json($taskCounts);

        const ctxTwo = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctxTwo, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'liczba zrealizowanych zadań',
                    data: taskCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })

    </script>
@endsection