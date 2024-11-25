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
        <div id="treeContainer" style="width: 100%; height: 600px; overflow: auto; border: 1px solid #ddd;"></div>
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

        if (!correlationData || Object.keys(correlationData).length === 0) {
            console.error('No correlation data available.');
            return;
        }

        // Prepare data for D3 tree with error handling
        const buildTree = (data, parentId = null) => {
            const tree = [];
            if (!data || typeof data !== 'object') {
                return tree; // If data is null or not an object, return an empty array
            }

            Object.keys(data).forEach(questionId => {
                const questionData = data[questionId];
                if (!questionData || typeof questionData !== 'object') return; // Skip invalid question data

                if (parentId === null || questionId === parentId) {
                    tree.push({
                        name: questionData.question || `Question ${questionId}`, // Fallback to "Question X"
                        children: questionData.answers
                            ? Object.keys(questionData.answers).map(answer => {
                                  const answerData = questionData.answers[answer];
                                  if (!answerData || typeof answerData !== 'object') return null; // Skip invalid answer data

                                  return {
                                      name: `${answer} (${answerData.count || 0} participants)`,
                                      children: buildTree(answerData.children) // Recursively build tree for child questions
                                  };
                              }).filter(child => child !== null) // Remove null entries
                            : []
                    });
                }
            });

            return tree;
        };

        const treeData = buildTree(correlationData);

        // Set up SVG dimensions
        const margin = { top: 20, right: 200, bottom: 20, left: 200 };
        const width = 1400;
        const height = 800;

        const svg = d3.select("#treeContainer").append("svg")
            .attr("width", "100%")
            .attr("height", height)
            .call(d3.zoom().on("zoom", function (event) {
                g.attr("transform", event.transform);
            }))
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        const g = svg.append("g");

        const root = d3.hierarchy({ children: treeData });

        // Tree layout configuration
        const treeLayout = d3.tree()
            .nodeSize([100, 300]) // Vertical: 100px, Horizontal: 300px
            .separation((a, b) => a.parent === b.parent ? 1 : 1.5);

        treeLayout(root);

        // Dynamically adjust height based on the number of nodes
        const totalHeight = Math.max(root.height * 120, height);
        svg.attr("height", totalHeight);

        // Add links between nodes
        g.selectAll(".link")
            .data(root.links())
            .enter().append("path")
            .attr("class", "link")
            .attr("fill", "none")
            .attr("stroke", "#ccc")
            .attr("stroke-width", 2)
            .attr("d", d3.linkHorizontal()
                .x(d => d.y)
                .y(d => d.x));

        // Add nodes
        const node = g.selectAll(".node")
            .data(root.descendants())
            .enter().append("g")
            .attr("class", "node")
            .attr("transform", d => `translate(${d.y},${d.x})`);

        // Node circles
        node.append("circle")
            .attr("r", 10)
            .attr("fill", d => (d.children ? "lightsteelblue" : "#fff"))
            .attr("stroke", "#000")
            .attr("stroke-width", 2);

        // Node labels
        node.append("text")
            .attr("dy", 3)
            .attr("x", d => (d.children ? -15 : 15))
            .attr("text-anchor", d => (d.children ? "end" : "start"))
            .text(d => d.data.name)
            .style("font-size", "12px");

        // Add tooltips for nodes
        node.append("title")
            .text(d => d.data.name);
    });
</script>



@endsection
