@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1>Survey Analytics</h1>

    <!-- Filter Section -->
    <form id="filterForm" method="GET" action="{{ route('responses.analytics') }}">
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="version" class="form-label">Select Question Version:</label>
                <select id="version" name="version" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    @foreach ($versions as $version)
                        <option value="{{ $version }}" {{ $version == $selectedVersion ? 'selected' : '' }}>
                            Version {{ $version }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Total Participants Section -->
    <div class="mb-4">
        <h4>Total Participants: {{ $totalParticipants }}</h4>
    </div>

    <!-- Chart Type Selector -->
    <div class="mb-3">
        <label for="chartType" class="form-label">Select Chart Type:</label>
        <select id="chartType" class="form-select">
            <option value="pie">Pie Chart</option>
            <option value="bar">Bar Chart</option>
        </select>
    </div>

    <!-- Display Question Analytics -->
    <div class="row">
        @foreach ($analyticsData as $questionId => $data)
            <div class="col-md-3 mb-4">
                <h6>Question {{ $questionId }}</h6>
                <canvas id="chart{{ $questionId }}" width="200" height="150"></canvas>
            </div>
        @endforeach
    </div>

    <!-- Correlation Section -->
    <div class="mt-5">
        <h2>Granular Data/Correlations</h2>
        <div id="treeContainer" style="width: 100%; height: 600px; border: 1px solid #ddd;"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const analyticsData = @json($analyticsData);

        // Chart type selector
        const chartTypeSelector = document.getElementById('chartType');
        let currentChartType = chartTypeSelector.value;

        chartTypeSelector.addEventListener('change', function () {
            currentChartType = this.value;
            generateCharts(); // Re-generate charts when the type changes
        });

        function generateCharts() {
            Object.keys(analyticsData).forEach(questionId => {
                const ctx = document.getElementById(`chart${questionId}`).getContext('2d');
                const data = analyticsData[questionId].answers;

                // Destroy any existing chart before creating a new one
                if (ctx.chart) ctx.chart.destroy();

                ctx.chart = new Chart(ctx, {
                    type: currentChartType, // Use the selected chart type
                    data: {
                        labels: Object.keys(data), // Answer labels
                        datasets: [{
                            label: 'Responses',
                            data: Object.values(data), // Answer counts
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                            ],
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                formatter: (value, ctx) => {
                                    const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1) + '%';
                                    return percentage;
                                },
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                },
                                anchor: 'center',
                                align: 'end',
                            },
                            title: {
                                display: true,
                                text: analyticsData[questionId].question,
                            }
                        },
                        scales: currentChartType === 'bar' ? {
                            x: { beginAtZero: true },
                            y: { beginAtZero: true }
                        } : {}, // Bar charts need axes, Pie charts don't
                    },
                    plugins: [ChartDataLabels] // Include datalabels plugin
                });
            });
        }

        // Initial chart generation
        generateCharts();
    });
</script>

<script src="https://d3js.org/d3.v6.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const correlationData = @json($correlationData);

        // Debug: Log correlationData to console
        console.log('Correlation Data:', correlationData);

        if (!correlationData || Object.keys(correlationData).length === 0) {
            console.error('No correlation data available.');
            return;
        }

        // Prepare data for D3 tree
        const treeData = Object.keys(correlationData).map(parentId => {
            const parent = correlationData[parentId];
            return {
                name: parent.question,
                children: Object.keys(parent.answers).map(answer => {
                    const answerData = parent.answers[answer];
                    return {
                        name: `${answer} (${answerData.count} participants)`,
                        children: Object.keys(answerData.children).map(childId => {
                            const childAnswers = answerData.children[childId];
                            return {
                                name: `Question ${childId}`,
                                children: Object.keys(childAnswers).map(childAnswer => ({
                                    name: `${childAnswer}: ${childAnswers[childAnswer]} participants`
                                }))
                            };
                        })
                    };
                })
            };
        });

        // D3 Tree Configuration
        const margin = { top: 20, right: 120, bottom: 20, left: 120 };
        const width = 1200 - margin.right - margin.left;
        const height = 800 - margin.top - margin.bottom;

        const svg = d3.select("#treeContainer").append("svg")
            .attr("width", width + margin.right + margin.left)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        const root = d3.hierarchy({ name: "Root", children: treeData });
        root.x0 = height / 2;
        root.y0 = 0;

        const treeLayout = d3.tree().size([height, width]);

        // Collapsible nodes
        function collapse(d) {
            if (d.children) {
                d._children = d.children;
                d._children.forEach(collapse);
                d.children = null;
            }
        }

        root.children.forEach(collapse);
        update(root);

        function update(source) {
            const treeData = treeLayout(root);
            const nodes = treeData.descendants();
            const links = treeData.links();

            nodes.forEach(d => d.y = d.depth * 180);

            // Nodes
            const node = svg.selectAll('g.node')
                .data(nodes, d => d.id || (d.id = ++i));

            const nodeEnter = node.enter().append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${source.y0},${source.x0})`)
                .on('click', (event, d) => {
                    d.children = d.children ? null : d._children;
                    update(d);
                });

            nodeEnter.append('circle')
                .attr('class', 'node')
                .attr('r', 10)
                .style('fill', d => d._children ? 'lightsteelblue' : '#fff');

            nodeEnter.append('text')
                .attr('dy', '.35em')
                .attr('x', d => d.children || d._children ? -13 : 13)
                .attr('text-anchor', d => d.children || d._children ? 'end' : 'start')
                .text(d => d.data.name)
                .style('fill-opacity', 1);

            const nodeUpdate = nodeEnter.merge(node);

            nodeUpdate.transition()
                .duration(200)
                .attr('transform', d => `translate(${d.y},${d.x})`);

            nodeUpdate.select('circle.node')
                .attr('r', 10)
                .style('fill', d => d._children ? 'lightsteelblue' : '#fff');

            nodeUpdate.select('text')
                .style('fill-opacity', 1);

            const nodeExit = node.exit().transition()
                .duration(200)
                .attr('transform', d => `translate(${source.y},${source.x})`)
                .remove();

            nodeExit.select('circle')
                .attr('r', 1e-6);

            nodeExit.select('text')
                .style('fill-opacity', 1e-6);

            // Links
            const link = svg.selectAll('path.link')
                .data(links, d => d.target.id);

            const linkEnter = link.enter().insert('path', 'g')
                .attr('class', 'link')
                .attr('d', d => {
                    const o = { x: source.x0, y: source.y0 };
                    return diagonal(o, o);
                });

            linkEnter.merge(link)
                .transition()
                .duration(200)
                .attr('d', d => diagonal(d.source, d.target));

            link.exit().transition()
                .duration(200)
                .attr('d', d => {
                    const o = { x: source.x, y: source.y };
                    return diagonal(o, o);
                })
                .remove();

            nodes.forEach(d => {
                d.x0 = d.x;
                d.y0 = d.y;
            });

            function diagonal(s, d) {
                return `M ${s.y} ${s.x}
                        C ${(s.y + d.y) / 2} ${s.x},
                          ${(s.y + d.y) / 2} ${d.x},
                          ${d.y} ${d.x}`;
            }
        }
    });
</script>
@endsection
